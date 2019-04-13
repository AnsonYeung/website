window.ready.then(function () {
	function Square(props) {
		let className = "square ";
		switch (props.value) {
			case 0:
				if (props.able) {
					className += "able";
				}
				break;
			case 1:
				className += "black";
				break;
			case 2:
				className += "white";
				break;
		}
		return (
			<div className={className} onClick={props.onClick}>{props.value === 0 || "⬤"}</div>
		);
	}

	Square.propTypes = {
		able: PropTypes.bool,
		value: PropTypes.number,
		onClick: PropTypes.func
	};

	const alld = [];
	for (let i = -1; i <= 1; ++i) {
		for (let j = -1; j <= 1; ++j) {
			if (i !== 0 || j !== 0) {
				alld.push([i, j]);
			}
		}
	}

	class Board extends React.Component {
		constructor(props) {
			super(props);
			const values = Array(this.props.size ** 2).fill(0);
			values[27] = values[36] = 1;
			values[28] = values[35] = 2;
			const ables = Array(this.props.size ** 2).fill(false);
			ables[20] = ables[29] = ables[34] = ables[43] = true;
			this.state = {
				values,
				ables,
				black: true
			};
		}

		inRange(num) {
			return num >= 0 && num < this.props.size;
		}

		emitEndEvent() {
			let bC = 0, wC = 0;
			for (const val of values) {
				if (val === 1) {
					++bC;
				} else if (val === 2) {
					++wC;
				}
			}
			const bWins = bC > wC;
			if (wC > bC) {
				let t = bC;
				bC = wC;
				wC = t;
			}
			this.props.onEnd(bWins, bC, wC);
		}

		getEatArray(numX, numY, flipCurrent, values) {
			const
				b = flipCurrent ^ this.state.black,
				myV = 2 - b,
				enemyV = 1 + b,
				eatArray = [];
			for (const d of alld) {
				let i = 0, posX = numX + d[0], posY = numY + d[1];
				while (this.inRange(posX) && this.inRange(posY) && values[posX + posY * 8] === enemyV) {
					++i;
					posX += d[0];
					posY += d[1];
				}
				if (this.inRange(posX) && this.inRange(posY) && values[posX + posY * 8] === myV) {
					for (let j = 1; j <= i; ++j) {
						eatArray.push(numX + numY * 8 + (d[0] + d[1] * 8) * j);
					}
				}
			}
			return eatArray;
		}

		handleClick(num) {
			if (this.state.values[num] !== 0 || !this.state.ables[num]) return;
			const
				values = this.state.values.slice(),
				ables = this.state.ables.slice(),
				myValue = 2 - this.state.black,
				numX = num % this.props.size,
				numY = (num - numX) / this.props.size,

			values[num] = myValue;
			for (const eat of this.getEatArray(numX, numY, false, values)) {
				values[eat] = myValue;
			}

			let oneAble = false;
			for (let i = 0; i < this.props.size; ++i) {
				for (let j = 0; j < this.props.size; ++j) {
					if (ables[i + j * 8] = values[i + j * 8] === 0 && this.getEatArray(i, j, true, values).length !== 0) {
						oneAble = true;
					}
				}
			}
			this.setState({values, ables, black: !this.state.black});
			this.props.onPlayer();
			if (!oneAble) {
				this.emitEndEvent();
			}
		}

		render() {
			const rows = [];
			for (let i = 0; i < this.props.size; ++i) {
				const squares = [];
				for (let j = 0; j < this.props.size; ++j) {
					const magicNum = i * 8 + j;
					squares.push(<Square key={magicNum} value={this.state.values[magicNum]} onClick={() => {this.handleClick(magicNum)}} able={this.state.ables[magicNum]}></Square>);
				}
				rows.push(<div className="board-row">{squares}</div>);
			}
			return (
				<div className="board">
					{rows}
				</div>
			);
		}
	}

	Board.propTypes = {
		size: PropTypes.number,
		onPlayer: PropTypes.func
	};

	class Game extends React.Component {
		constructor(props) {
			super(props);
			this.state = {
				black: true,
				end: false
			};
		}

		getInfo() {
			if (this.state.end) {
				if (this.state.gS === this.state.lS) {
					return `Game ends as player ${this.state.black ? "black" : "white"} has no move. The game ends in a draw (${this.state.gS}:${this.state.lS})`;
				} else {
					return `Game ends as player ${this.state.black ? "black" : "white"} has no move. ${this.state.bWins ? "Black" : "White"} wins by ${this.state.gS}:${this.state.lS}`;
				}
			} else {
				return `Player ${2 - this.state.black} (${this.state.black ? "Black" : "White"}'s turn)`;
			}
		}

		render() {
			return (
				<div className="game">
					<div className="player-info">{this.getInfo()}</div>
					<Board size={8} onPlayer={() => {this.setState({black: !this.state.black})}} onEnd={(bWins, gS, lS) => {this.setState({bWins, gS, lS, end: true})}}/>
				</div>
			);
		}
	}

	ReactDOM.render(
		<Game />,
		document.getElementById("base")
	);
});
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

		handleClick(num) {
			if (this.state.values[num] !== 0 || !this.state.ables[num]) return;
			const
				values = this.state.values.slice(),
				ables = this.state.ables.slice(),
				enemyValue = 1 + this.state.black,
				alld = [],
				numX = num % this.props.size,
				numY = (num - numX) / this.props.size;
			for (let i = -1; i <= 1; ++i) {
				for (let j = -1; j <= 1; ++j) {
					if (i !== 0 || j !== 0) {
						alld.push([i, j]);
					}
				}
			}

			values[num] = 2 - this.state.black;
			for (const d of alld) {
				let i = 0, posX = numX + d[0], posY = numY + d[1];
				while (this.inRange(posX) && this.inRange(posY) && values[posX + 8 * posY] === enemyValue) {
					++i;
					posX += d[0];
					posY += d[1];
				}
				if (this.inRange(posX) && this.inRange(posY) && values[posX + 8 * posY] === 2 - this.state.black) {
					for (let j = 1; j <= i; ++j) {
						values[num + (d[0] + 8 * d[1]) * j] = 2 - this.state.black;
					}
				}
			}
			let oneAble = false;
			for (let i = 0; i < this.props.size; ++i) {
				for (let j = 0; j < this.props.size; ++j) {
					ables[i + 8 * j] = false;
					if (values[i + 8 * j] === 0) {
						for (const d of alld) {
							let posX = i + d[0], posY = j + d[1];
							while (this.inRange(posX) && this.inRange(posY) && values[posX + 8 * posY] === 2 - this.state.black) {
								posX += d[0];
								posY += d[1];
							}
							if ((posX !== i + d[0] || posY !== j + d[1]) && this.inRange(posX) && this.inRange(posY) && values[posX + 8 * posY] === enemyValue) {
								ables[i + 8 * j] = oneAble = true;
								break;
							}
						}
					}
				}
			}
			this.setState({values, ables, black: !this.state.black});
			this.props.onPlayer();
			if (!oneAble) {
				// trigger game endding
				alert("Game over, currently in development.");
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
				black: true
			};
		}

		render() {
			return (
				<div className="game">
					<div className="player-info">{"Player " + (2 - this.state.black)  + " (" + (this.state.black ? "Black" : "White") + "'s turn)"}</div>
					<Board size={8} onPlayer={() => {this.setState({black: !this.state.black})}}/>
				</div>
			);
		}
	}

	ReactDOM.render(
		<Game />,
		document.getElementById("base")
	);
});
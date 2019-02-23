window.ready.then(function () {
	class Square extends React.Component {
		render() {
			return (
				<div className="square">O</div>
			);
		}
	}

	class Board extends React.Component {
		render() {
			let rows = [];
			for (let i = 0; i < this.props.size; i++) {
				let squares = [];
				for (let j = 0; j < this.props.size; j++) {
					squares.push(<Square key={j}></Square>);
				}
				rows.push(<div className="row" key={i}>{squares}</div>);
			}
			return (
				<div className="board">
					{rows}
				</div>
			);
		}
	}

	Board.propTypes = {
		size: PropTypes.number
	};

	class Game extends React.Component {
		render() {
			return (
				<div className="game">
					<div className="player-info">Player 1 (Black's turn)</div>
					<Board size={8}/>
				</div>
			);
		}
	}

	ReactDOM.render(
		<Game />,
		document.getElementById("base")
	);
});
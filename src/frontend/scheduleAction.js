export default (actionCreator, interval, dispatch) => {
  setInterval(() => dispatch(actionCreator()), interval);
};

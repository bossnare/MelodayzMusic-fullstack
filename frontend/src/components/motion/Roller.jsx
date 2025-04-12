import PropTypes from "prop-types"

export const Roller = ({className}) => {
  return (
    <div id='roller' className={className} ></div>
  )
}

Roller.propTypes = {
  className: PropTypes.string.isRequired,
};
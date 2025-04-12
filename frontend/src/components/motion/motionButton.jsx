import { motion } from "motion/react";
import PropTypes from "prop-types";

export const Button = ({ classname, value, eventHandler, disabled, type }) => {
  return (
    <motion.button
      whileHover={{ scale: 1.01 }}
      whileTap={{ scale: 0.95 }}
      className={classname}
      onClick={eventHandler}
      type={type}
      disabled={disabled}
    >
      {value}
    </motion.button>
  );
};

export const Div = ({ classname, value, eventHandler }) => {
  return (
    <motion.div
      whileHover={{ scale: 1.01 }}
      whileTap={{ scale: 0.95 }}
      className={classname}
      onClick={eventHandler}
    >
      {value}
    </motion.div>
  );
};

export const Label = ({ classnames, label, htmlFor }) => {
  return (
    <motion.label
      whileHover={{ scale: 1.01 }}
      whileTap={{ scale: 0.95 }}
      className={classnames}
      htmlFor={htmlFor}
    >
      {label}
    </motion.label>
  );
};

Button.propTypes = {
  value: PropTypes.node.isRequired,
  classname: PropTypes.node.isRequired,
  eventHandler: PropTypes.node.isRequired,
  disabled: PropTypes.node.isRequired,
  type: PropTypes.node.isRequired,
};

Div.propTypes = {
  value: PropTypes.node.isRequired,
  classname: PropTypes.node.isRequired,
  eventHandler: PropTypes.node.isRequired,
};

Label.propTypes = {
  classnames: PropTypes.node.isRequired,
  label: PropTypes.node.isRequired,
  htmlFor: PropTypes.node.isRequired,
};

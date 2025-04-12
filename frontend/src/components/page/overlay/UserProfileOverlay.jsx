import { motion } from "motion/react";
import PropTypes from "prop-types";

export const UserProfileOverlay = ({ message='Une erreur est survenue. Veuillez actualiser ou réessayer plus tard.', refetch, isPending }) => {
  return (
    <div id='overlay' className="!fixed !z-[9999] bg-black/50 transition-opacity select-none duration-100 ease-in backdrop-blur-xs !inset-0 flex justify-center items-center flex-col text-blue-500">
      <motion.div
        initial={{ opacity: 0 }}
        animate={{
          opacity: isPending ? 0 : 1,
          scale: isPending ? 0.6 : [0.6, 1.2, 0.9, 1],
        }}
        transition={{ duration: 0.5, ease: "easeInOut" }}
        className="bg-white/90 rounded-lg overflow-hidden backdrop-blur-lg max-w-xs md:max-w-sm flex md:min-h-40 justify-between items-center flex-col gap-2"
      >
        <div className="font-extralight text-gray-900 text-wrap p-4">
          {message}
        </div>
        <button
          className="text-center font-semibold p-2 w-full border-gray-300 hover:bg-gray-200 active:bg-gray-300 border-t-2"
          onClick={refetch}
        >
          OK
        </button>
      </motion.div>
    </div>
  );
};

UserProfileOverlay.propTypes = {
  message: PropTypes.node.isRequired,
  refetch: PropTypes.node.isRequired,
  isPending: PropTypes.node.isRequired,
};

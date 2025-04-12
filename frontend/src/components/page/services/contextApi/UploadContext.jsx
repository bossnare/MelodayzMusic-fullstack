import PropTypes from "prop-types";
import { createContext, useState } from "react";

export const UploadContext = createContext();

export const UploadProvider = ({ children }) => {
  const [isShow, setIsShow] = useState(false);
    const [isLoading, setIsLoading ] = useState(false)

    const handleButtonPlus = () => {
        setIsLoading(true)
      setTimeout(() => {
        setIsShow(!isShow);
        setIsLoading(false)
      }, 2000);
    };


  return (
    <UploadContext value={{ isShow, isLoading, handleButtonPlus, setIsShow }}>
      {children}
    </UploadContext>
  );
};

export default UploadProvider;

UploadProvider.propTypes = {
  children: PropTypes.node.isRequired,
};

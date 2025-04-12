import PropTypes from "prop-types";
import { createContext, useState } from "react";

export const FavoriteContext = createContext();

export const FavoriteProvider = ({ children }) => {
  const [favorites, setFavorites] = useState([]);

  const addFavorite = (songId) => {
    setFavorites((prevFavorites) => [...prevFavorites, songId]);
  };

  const removeFavorite = (songId) => {
    setFavorites((prevFavorites) =>
      prevFavorites.filter((id) => id !== songId)
    );
  };

  return (
    <FavoriteContext value={{ favorites, addFavorite, removeFavorite }}>
      {children}
    </FavoriteContext>
  );
};

export default FavoriteProvider;

FavoriteProvider.propTypes = {
  children: PropTypes.node.isRequired,
};

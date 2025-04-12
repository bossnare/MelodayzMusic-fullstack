import PropTypes from "prop-types";
import { createContext, useEffect, useRef, useState } from "react";
import { useNavigate } from "react-router-dom";

export const AudioContext = createContext();

export const AudioProvider = ({ children }) => {
  const [currentTrack, setCurrentTrack] = useState(null);
  const [isPlaying, setIsPlaying] = useState(false);
  const [isRunning, setIsRunning] = useState(false);
  const navigate = useNavigate()

  const audioRef = useRef(new Audio());

  useEffect(() => {
    if (currentTrack) {
      audioRef.current.src = currentTrack.fileUrl;
      audioRef.current.play();
    }

    // const handleEnd = () => {
    //   if (currentTrack) {
    //     setIsPlaying(false);
    //     audioRef.current.pause()
    //   }
    // };
 
  }, [currentTrack]);

  const playTrack = (song) => {
    if (currentTrack?.id === song.id && isPlaying) {
      // Raha mitovy ilay hira, toggle play/pause
      setIsRunning(true);
    } else {
      setCurrentTrack(song);
      setIsPlaying(true);
      navigate(`dashboard/song/${song.id}`)
     
    }
  };

  const togglePlayPause = () => {
    if (!audioRef.current) return;
    if (isPlaying) {
      audioRef.current.pause();
    } else {
      audioRef.current.play();
    }
    setIsPlaying(!isPlaying);
  };

  return (
    <AudioContext
      value={{ currentTrack, isPlaying, setIsPlaying, playTrack, togglePlayPause, isRunning }}
    >
      {children}
    </AudioContext>
  );
};

export default AudioProvider;

AudioProvider.propTypes = {
  children: PropTypes.node.isRequired,
};

// import { useContext } from "react";
// import { AudioContext } from "../services/contextApi/AudioContext";
import { Button, Div } from "../../motion/motionButton";
import {
  Waveform,
  Play,
  Pause,
  ArrowClockwise,
  ArrowCounterClockwise,
  WaveformSlash,
} from "@phosphor-icons/react";
import { useAudioStore } from "../services/contextApi/Zustand";
import { Roller } from "../../motion/Roller";
import { useLocation, useNavigate } from "react-router-dom";

export const Player = () => {
  const { currentTrack, isPlaying, togglePlayPause, playTrack, isLoading } =
    useAudioStore();
  const location = useLocation();
  const isHidden = location.pathname.startsWith("/dashboard/song/");
  const navigate = useNavigate();

  return (
    <>
      {currentTrack && (
        <div
          className={`${
            !currentTrack && !isPlaying ? "opacity-0 z-0" : "block z-20"
          } cursor-pointer hover:bg-black/5 fixed w-48 sm:w-1/3 md:w-1/4 lg:w-1/5 backdrop-blur-xs bg-white/5 border-black/5 border-b-0 border-2 rounded-lg p-2 lg:p-3 lg:!pt-[1px] shadow-lg  transition-all duration-150 ease-in-out will-change-auto ${
            isHidden ? "opacity-0 z-0 -bottom-70" : "opacity-100 bottom-20 z-20"
          } right-4`}
        >
          <div>
            <Div
              value={
                <div className="bg-gray-400 w-1/6 mx-auto py-1 rounded-full my-1 mb-2 hover:bg-gray-400/80"></div>
              }
            />
            <Div
              eventHandler={() => {
                playTrack(currentTrack, navigate);
              }}
              value={
                <div className=" bg-amber-50 relative overflow-hidden rounded-md">
                  <img
                    src={
                      currentTrack?.songCover?.coverUrl ||
                      currentTrack?.defaultCover
                    }
                    alt="cover"
                    className="aspect-[16/9] object-cover"
                  />
                  <div className="text-xl sm:text-3xl lg:text-3xl absolute bottom-1 text-white left-1">
                    {isPlaying ? (
                      <Waveform weight="bold" />
                    ) : (
                      <WaveformSlash weight="bold" />
                    )}
                  </div>
                </div>
              }
            />

            {isLoading ? (
              <Roller className={"!text-blue-600"} />
            ) : (
              <>
                <h1 className="text-xl truncate text-nowrap mt-2 text-gray-800">
                  {currentTrack.title}
                </h1>
                <p className="text-gray-500">{currentTrack.artist}</p>
              </>
            )}
            <div className="*:size-8 *:rounded-full flex justify-center items-center *:text-gray-600 space-x-2 gap-4 *:bg-black/4 *:hover:bg-black/13 *:active:bg-black/15 mt-2 *:flex *:justify-center *:items-center">
              <Button
                value={
                  <>
                    <ArrowCounterClockwise />
                    <span className="absolute text-[9px] left-0 right-0">
                      10
                    </span>
                  </>
                }
                classname={"text-md md:text-lg xl:text-2xl relative"}
              />
              <Button
                eventHandler={() => {
                  togglePlayPause();
                }}
                value={
                  isPlaying ? <Pause weight="fill" /> : <Play weight="fill" />
                }
                classname={"text-md md:text-lg xl:text-2xl !size-10"}
              />

              <Button
                value={
                  <>
                    <ArrowClockwise />
                    <span className="absolute text-[9px] left-0 right-0">
                      10
                    </span>
                  </>
                }
                classname={"text-md md:text-lg xl:text-2xl relative"}
              />
            </div>
          </div>
        </div>
      )}
    </>
  );
};

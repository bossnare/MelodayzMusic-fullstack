import {
  Play,
  Pause,
  ChatCircle,
  Heart,
  SkipForward,
  SkipBack,
  SpeakerHigh,
  SpeakerSlash,
  PaperPlaneTilt,
} from "@phosphor-icons/react";
import { Button, Div, Label } from "../../motion/motionButton";
import dateAgo from "../services/api/date";
import { useAudioStore } from "../services/contextApi/Zustand";
import { Roller } from "../../motion/Roller";
import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { Playing } from "../../motion/Playing";
import apis from "../services/api/apis";

export const SongPage = () => {
  const navigate = useNavigate();
  const {
    currentTrack,
    isPlaying,
    togglePlayPause,
    isLoading,
    duration,
    progress,
    setProgress,
    playPrev,
    playNext,
  } = useAudioStore();
  const [isSpeak, setIsSpeak] = useState(true);
  const [loading, setLoading] = useState(false);
  const commentaires = currentTrack.comments
  // const [commentaires, setCommentaires] = useState(currentTrack.comments);
  const [formData, setFormData] = useState({ content: "" });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
    console.log(formData);
  };

  const sendComment = async (trackId) => {
    setLoading(true);
    try {
      const response = await apis.post(
        `/api/comments/post/${trackId}`,
        formData,
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      );
      const res = await response.data;
      if (res) {
        formData.content = "";
        console.log(res);
      }
    } catch (e) {
      if (e.response.data) {
        console.log(e.response.data);
      }
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (!currentTrack && !isPlaying) {
      console.log("tsy izy");
      navigate("/dashboard");
    } else {
      console.log("tsy izy");
    }
  }, [currentTrack, navigate, isPlaying]);

  return (
    <section className="bg-amer-100">
      {isLoading ? (
        <Roller />
      ) : (
        <div className="container z-1">
          <div className="grid grid-cols-3 items-start gap-4">
            <div className="col-span-full font-black flex justify-between items-center  bg-gray-100">
              <h1 className="text-xl text-gray-800">
                <span className="!font-thin text-gray-400">Titre:</span>{" "}
                {currentTrack?.title}
              </h1>
              <p className="text-lg text-gray-600">
                <span className="!font-thin text-gray-400">Artiste:</span>{" "}
                {currentTrack?.artist}
              </p>
            </div>
            <Div
              value={
                <div className="sm:flex-1/3 col-span-1 shrink-0 cursor-pointer relative overflow-hidden rounded-t-md">
                  <figure className="bg-gray-100 shrink-0 h-40 sm:h-50 md:w-full w-full md:h-40 lg:h-50 xl:h-60 relative">
                    <img
                      src={
                        currentTrack?.songCover?.coverUrl ||
                        currentTrack?.defaultCover
                      }
                      alt="cover"
                      className="object-cover h-full w-full"
                      loading="lazy"
                    />
                    <span
                      className={` ${
                        isPlaying && isSpeak && "opacity-100"
                      } duration-150 transition-opacity ease-in opacity-0 absolute bottom-0 right-0 left-0 h-15 bg-am0 flex justify-center`}
                    >
                      <Playing />
                    </span>
                  </figure>

                  <div>
                    <input
                      type="range"
                      min="0"
                      max={duration}
                      value={progress}
                      onChange={(e) => setProgress(e.target.value)}
                      className="w-full cursor-pointer text-amber-200"
                    />
                  </div>
                  <div className="flex grow flex-none py-2 md:py-1 gap-2 px-2  *:flex  *:p-1 *:bg-aber-100 *text-center justify-center rounded-b-lg border-1 border-gray-200  items-center">
                    <div className="*:size-8 *:rounded-full flex justify-center items-center *:text-gray-600 space-x-2 gap-4 *:bg-black/4 *:hover:bg-black/13 *:active:bg-black/15 mt-2 *:flex *:justify-center *:items-center">
                      <Button
                        eventHandler={() => {
                          setIsSpeak(!isSpeak);
                        }}
                        value={
                          isSpeak ? (
                            <SpeakerHigh weight="regular" />
                          ) : (
                            <SpeakerSlash weight="bold" />
                          )
                        }
                        classname={"text-md md:text-lg xl:text-2xl"}
                      />
                      <Button
                        value={<SkipBack />}
                        classname={"text-md md:text-lg xl:text-2xl"}
                        eventHandler={playPrev}
                      />
                      <Button
                        eventHandler={() => {
                          togglePlayPause();
                        }}
                        value={
                          isPlaying ? (
                            <Pause weight="fill" />
                          ) : (
                            <Play weight="fill" />
                          )
                        }
                        classname={"text-md md:text-lg xl:text-2xl !size-10"}
                      />

                      <Button
                        value={<SkipForward />}
                        classname={"text-md md:text-lg xl:text-2xl"}
                        eventHandler={playNext}
                      />
                      <Button
                        value={<Heart className="text-2xl md:text-xl" />}
                      />
                    </div>
                  </div>
                </div>
              }
            />

            <div className="col-span-2 flex flex-col !relative h-[calc(100%)]">
              <div className="bg-gray-200 p-2 flex flex-wrap gap-2 rounded-lg">
                <div className="!size-12 md:size-8 shrink-0 outline-hidden rounded-full overflow-hidden border-gray-200 border-2 ">
                  <img
                    src={
                      currentTrack?.userOwner?.activateProfilePicture
                        ?.pictureUrl || currentTrack?.userOwner?.defaultPicture
                    }
                    alt="photoDP"
                    loading="lazy"
                    className="object-cover h-full w-full"
                  />
                </div>
                <span className="md:w-[calc(100%-300px)] shrink-0 w-[calc(100%-56px)] mt-1 inline-block font-bold line-clamp-1 grow h-10 md:h-auto">
                  {currentTrack?.userOwner?.username}
                </span>
                <span className="!w-40 text-sm pt-1 text-gray-900 font-medium text-right">
                  {dateAgo(currentTrack?.createdAt || new Date())}
                </span>

                <p className="line-clamp-4 w-full h-full flex-none md:line-clamp-2 top-0 text-gray-600 font-medium cursor-pointer">
                  {currentTrack?.description}
                </p>
              </div>

              {/* comment list */}
              <ul className=" bg-gray-100 p-1 space-y-4 text-gray-500 max-h-50 overflow-y-scroll">
                {commentaires.map((comment) => (
                  <li key={comment.id}>{comment.content}</li>
                ))}
              </ul>

              <div className="flex mt-auto flex-none md:py-1 *:flex  *:p-1 *:bg-aber-100 *text-center justify-center rounded-b-lg border-1 border-gray-300  items-center">
                <div className="gap-5 flex-1/4 flex  *:hover:bg-gray-100 *:active:bg-gray-300">
                  <Label
                    label={<ChatCircle className="text-2xl md:text-2xl" />}
                    classnames={"flex items-center cursor-pointer"}
                    htmlFor={"comment"}
                  />
                  <input
                    id="comment"
                    role="commentaire"
                    name="content"
                    type="text"
                    placeholder="Votre commentaire..."
                    className={`${
                      !loading && "animate-pulse"
                    } focus:border focus:border-gray-300 w-full rounded-full py-4`}
                    value={formData.content}
                    onChange={handleChange}
                  />
                  <Button
                    type={"button"}
                    value={<PaperPlaneTilt className="text-2xl md:text-2xl" />}
                    eventHandler={() => {
                      sendComment(currentTrack.id);
                    }}
                  />
                </div>
              </div>
            </div>
          </div>
          <h2 className="mt-6 text-2xl font-bold">Recommandations</h2>
        </div>
      )}
    </section>
  );
};

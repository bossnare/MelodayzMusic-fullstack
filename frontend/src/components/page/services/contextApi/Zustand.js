import { create } from "zustand";
// import playCount from "../api/playCount";

export const useAudioStore = create((set, get) => ({
  currentTrack: null,
  isPlaying: false,
  isLoading: false,
  queue: [],
  audioRef: new Audio(),
  progress: 0,
  duration: 0,

  playTrack: async (song, navigate) => {
    const { currentTrack, audioRef } = get();

    if (currentTrack?.id === song.id) {
      navigate(`/dashboard/song/${song.id}`);
    } else {
      set({
        currentTrack: song,
        isPlaying: false,
        isLoading: true,
        progress: 0,
        duration: song.fileDuration,
      });
      navigate(`/dashboard/song/${song.id}`);

      audioRef.src = song.fileUrl;

      try {
        await audioRef.play();
        set({ isPlaying: true, isLoading: false });

        // Alefa ilay request API playCount raha hiraina voalohany
        // await playCount(song.id);
      } catch (error) {
        console.log(error);
        set({ isPlaying: false, isLoading: false });
      }
    }
  },

  togglePlayPause: () => {
    const { isPlaying, audioRef } = get();
    isPlaying ? audioRef.pause() : audioRef.play();
    set({ isPlaying: !isPlaying });
  },

  addToQueue: (song) => {
    set((state) => ({ queue: [...state.queue, song] }));
  },

  playNext: async () => {
    const { queue, audioRef } = get();
    if (queue.length > 0) {
      const nextTrack = queue[0];
      set({
        currentTrack: nextTrack,
        queue: queue.slice(1),
        isPlaying: false,
        isLoading: true,
        progress: 0,
        duration: nextTrack.fileDuration,
      });

      audioRef.src = nextTrack.fileUrl;
      try {
        await audioRef.play();
        set({ isPlaying: true, isLoading: false });

        // Alefa koa ilay playCount raha mivoaka amin'ny queue
        // await playCount(nextTrack.id);
      } catch (error) {
        console.log(error);
        set({ isPlaying: false, isLoading: false });
      }
    } else {
      set({ currentTrack: null, isPlaying: false });
    }
  },

  playPrev: async () => {
    const { queue, currentTrack, audioRef } = get();
    const prevTrack = queue.length > 0 ? queue[queue.length - 1] : null;

    if (prevTrack) {
      set({
        currentTrack: prevTrack,
        isPlaying: false,
        isLoading: true,
        progress: 0,
        duration: prevTrack.fileDuration,
      });

      audioRef.src = prevTrack.fileUrl;
      try {
        await audioRef.play();
        set({ isPlaying: true, isLoading: false });

        // await playCount(prevTrack.id);
      } catch (error) {
        console.log(error);
        set({ isPlaying: false, isLoading: false });
      }
    }
  },

  updateProgress: () => {
    const { audioRef } = get();
    set({ progress: audioRef.currentTime });
  },

  setProgress: (value) => {
    const { audioRef } = get();
    audioRef.currentTime = value;
    set({ progress: value });
  },
}));

// Manampy event listener rehefa tapitra ilay hira
useAudioStore.getState().audioRef.addEventListener("ended", () => {
  useAudioStore.getState().playNext();
});

// Manampy event listener ho an'ny progress bar
useAudioStore.getState().audioRef.addEventListener("timeupdate", () => {
  useAudioStore.getState().updateProgress();
});

import apis from "./apis";

const playCount = async (songId) => {
  try {
    const response = await apis.post(`/api/songs/${songId}/play`, {
        headers: {
           'Content-Type': 'application/json'
        }
    });
    const playData = await response.data;
    console.log(playData);
  } catch (error) {
    if (error.response.data) {
      return console.error(error.response.data);
    } else {
      return console.log("Misy error maventy");
    }
  }
};

export default playCount;

import axios from "axios";

//creation d'instance axios
const apis = axios.create({
  baseURL: "http://localhost:8000",
});

apis.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers = {
        ...config.headers,
        Authorization: `Bearer ${token}`,
      }; //apetraka ao amin'ny header ny azo haha
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default apis;

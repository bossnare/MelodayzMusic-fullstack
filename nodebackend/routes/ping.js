import express from "express"; 
const routes = express.Router(); // mila an'io raha misaraka front-back

routes.get("/ping", (req, res) => {
  res.json({ message: "Pong from Node backend 🥁" });
});

export default routes;


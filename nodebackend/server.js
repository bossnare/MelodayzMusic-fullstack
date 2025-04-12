import express from 'express';
import connectDB from './config/db.js';
import dotenv from 'dotenv';
import cors from 'cors'; // mila an'io raha misaraka front-back
import ping from './routes/ping.js'; // mila an'io raha misaraka front-back

dotenv.config();

const app = express();
app.use(cors()); // 🔥 Mila an'io raha misaraka front-back
// Connect to MongoDB
// manao connectDB();
connectDB();
// Middleware to parse JSON requests
// manao parse JSON requests
app.use(express.json());
// Define a simple route
app.get('/', (req, res) => {
  res.send('Hello World! My Server is Running');
});

app.use('/api', ping);
// Define your routes here
// Start the server
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
// Export the app for testing purposes
export default app;
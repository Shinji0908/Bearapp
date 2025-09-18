const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
require('dotenv').config();

const app = express();

// Middleware
app.use(cors());
app.use(express.json());

// Debug middleware to log all requests
app.use((req, res, next) => {
  console.log(`📥 ${req.method} ${req.url} - Headers:`, req.headers);
  next();
});


// Routes
app.use('/api/incidents', require('./routes/incidents'));
app.use("/api/auth", require("./routes/auth"));
app.use("/api/admin", require("./routes/admin"));


// MongoDB connect
mongoose.connect(process.env.MONGO_URI)
    .then(() => console.log('✅ MongoDB Connected'))
    .catch(err => console.error('❌ MongoDB Connection Error:', err.message));

const PORT = process.env.PORT || 5000;

// ✅ Listen on all interfaces (PC + Emulator + LAN)
app.listen(PORT, "0.0.0.0", () => {
    console.log(`🚀 Server running on http://0.0.0.0:${PORT}`);
    console.log(`📌 Local: http://localhost:${PORT}`);
    console.log(`📱 Emulator: http://10.0.2.2:${PORT}`);
});
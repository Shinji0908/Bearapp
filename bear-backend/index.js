const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
const path = require('path');
require('dotenv').config();
const http = require('http');
const { Server } = require('socket.io');

const app = express();

// Middleware
app.use(cors());
app.use(express.json());

// Serve uploaded files
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

// Debug middleware to log all requests
app.use((req, res, next) => {
  console.log(`📥 ${req.method} ${req.url} - Headers:`, req.headers);
  next();
});

// Routes
app.use('/api/incidents', require('./routes/incidents'));
app.use("/api/auth", require("./routes/auth"));
app.use("/api/admin", require("./routes/admin"));
app.use("/api/users", require("./routes/users"));
app.use("/api/verification", require("./routes/verification"));

// Health check endpoint
app.get('/api/health', (req, res) => {
  res.json({ status: 'OK', message: 'Server is running' });
});

// MongoDB connect
mongoose.connect(process.env.MONGO_URI || 'mongodb://localhost:27017/bear-system')
    .then(() => console.log('✅ MongoDB Connected'))
    .catch(err => {
        console.error('❌ MongoDB Connection Error:', err.message);
        console.log('⚠️  Server will continue without database connection');
    });

const PORT = process.env.PORT || 5000;

// Create HTTP server and attach Socket.IO
const server = http.createServer(app);
const io = new Server(server, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"],
    credentials: true
  }
});

// Expose io to routes
app.set('io', io);

// Socket.IO events
io.on('connection', (socket) => {
  console.log(`🔌 Client connected: ${socket.id}`);

  socket.on('joinIncident', ({ incidentId }) => {
    if (!incidentId) return;
    const room = `incident:${incidentId}`;
    socket.join(room);
    console.log(`👥 ${socket.id} joined ${room}`);
    socket.emit('joinedIncident', { incidentId });
  });

  socket.on('leaveIncident', ({ incidentId }) => {
    if (!incidentId) return;
    const room = `incident:${incidentId}`;
    socket.leave(room);
    console.log(`👋 ${socket.id} left ${room}`);
  });

  socket.on('disconnect', (reason) => {
    console.log(`🔌 Client disconnected: ${socket.id} (${reason})`);
  });
});

// ✅ Listen on all interfaces (PC + Emulator + LAN)
server.listen(PORT, "0.0.0.0", () => {
  console.log(`🚀 Server (HTTP) on http://0.0.0.0:${PORT}`);
  console.log(`📌 Local: http://localhost:${PORT}`);
  console.log(`📱 Emulator: http://10.0.2.2:${PORT}`);
});
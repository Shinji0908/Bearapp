import React, { useState, useEffect } from "react";
import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";
import { ThemeProvider } from "@mui/material/styles";
import { CssBaseline } from "@mui/material";
import { io } from "socket.io-client";
import bearTheme from "./theme/bearTheme";
import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";
import Incidents from "./pages/Incidents";
import Users from "./pages/Users";
import Responders from "./pages/Responders";
import Reports from "./pages/Reports";
import ActivityLogs from "./pages/ActivityLogs";
import VerifyAccounts from "./pages/VerifyAccounts";
import NotificationSystem from "./components/NotificationSystem";

// Protected Route Component
function ProtectedRoute({ children }) {
  const token = localStorage.getItem("token");
  return token ? children : <Navigate to="/login" replace />;
}

function App() {
  const [socket, setSocket] = useState(null);

  useEffect(() => {
    // Only connect to socket if user is logged in
    const token = localStorage.getItem("token");
    if (token) {
      const newSocket = io(process.env.REACT_APP_SOCKET_URL || "http://localhost:5000", { 
        transports: ["websocket"] 
      });
      
      newSocket.on("connect", () => {
        console.log("✅ Global Socket connected:", newSocket.id);
      });
      
      newSocket.on("disconnect", (reason) => {
        console.log("🔌 Global Socket disconnected:", reason);
      });
      
      setSocket(newSocket);
      
      return () => {
        newSocket.disconnect();
      };
    }
  }, []);

  return (
    <ThemeProvider theme={bearTheme}>
      <CssBaseline />
      <Router>
        {/* Global Notification System */}
        {socket && <NotificationSystem socket={socket} />}
        
        <Routes>
        {/* Public Routes */}
        <Route path="/login" element={<Login />} />
        
        {/* Protected Routes */}
        <Route 
          path="/dashboard" 
          element={
            <ProtectedRoute>
              <Dashboard />
            </ProtectedRoute>
          } 
        />
        <Route 
          path="/incidents" 
          element={
            <ProtectedRoute>
              <Incidents />
            </ProtectedRoute>
          } 
        />
        <Route 
          path="/residents" 
          element={
            <ProtectedRoute>
              <Users />
            </ProtectedRoute>
          } 
        />
        <Route 
          path="/verify-accounts" 
          element={
            <ProtectedRoute>
              <VerifyAccounts />
            </ProtectedRoute>
          } 
        />
        <Route 
          path="/responders" 
          element={
            <ProtectedRoute>
              <Responders />
            </ProtectedRoute>
          } 
        />
        <Route 
          path="/reports" 
          element={
            <ProtectedRoute>
              <Reports />
            </ProtectedRoute>
          } 
        />
        <Route 
          path="/activity" 
          element={
            <ProtectedRoute>
              <ActivityLogs />
            </ProtectedRoute>
          } 
        />
        
        {/* Default redirect */}
        <Route path="/" element={<Navigate to="/dashboard" replace />} />
        <Route path="*" element={<Navigate to="/dashboard" replace />} />
        </Routes>
      </Router>
    </ThemeProvider>
  );
}

export default App;

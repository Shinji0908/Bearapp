import React, { useState, useEffect } from "react";
import {
  Box,
  Typography,
  Card,
  CardContent,
  Grid,
} from "@mui/material";
import {
  Dashboard as DashboardIcon,
  Emergency,
  People,
  Settings,
} from "@mui/icons-material";
import { useNavigate } from "react-router-dom";
import DashboardSidebar from "../components/DashboardSidebar";
import NotificationHistory from "../components/NotificationHistory";

function Dashboard() {
  const [user, setUser] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    // Check if user is logged in
    const token = localStorage.getItem("token");
    const userData = localStorage.getItem("user");
    
    if (!token || !userData) {
      navigate("/login");
      return;
    }

    try {
      setUser(JSON.parse(userData));
    } catch (err) {
      console.error("Error parsing user data:", err);
      navigate("/login");
    }
  }, [navigate]);

  const handleLogout = () => {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    navigate("/login");
  };

  if (!user) {
    return (
      <Box sx={{ display: "flex", justifyContent: "center", alignItems: "center", height: "100vh" }}>
        <Typography>Loading...</Typography>
      </Box>
    );
  }


  const dashboardCards = [
    {
      title: "Active Incidents",
      value: "12",
      icon: <Emergency sx={{ fontSize: 40 }} />,
      color: "var(--bear-red)",
      description: "Emergency incidents requiring attention",
    },
    {
      title: "Total Residents",
      value: "1,234",
      icon: <People sx={{ fontSize: 40 }} />,
      color: "var(--bear-blue)",
      description: "Registered residents in the system",
    },
    {
      title: "Responders",
      value: "45",
      icon: <Settings sx={{ fontSize: 40 }} />,
      color: "var(--bear-status-approved)",
      description: "Active emergency responders",
    },
    {
      title: "Response Time",
      value: "3.2 min",
      icon: <DashboardIcon sx={{ fontSize: 40 }} />,
      color: "var(--bear-yellow)",
      description: "Average emergency response time",
    },
  ];

  return (
    <Box sx={{ display: "flex", height: "100vh" }}>
      {/* Sidebar */}
      <DashboardSidebar user={user} onLogout={handleLogout} />

      {/* Main Content Area */}
      <Box sx={{ flexGrow: 1, display: "flex", flexDirection: "column" }}>
        {/* Main Content */}
        <Box sx={{ p: 3, flex: 1, overflow: "auto", backgroundColor: "var(--bear-body)" }}>
          <Typography variant="h4" gutterBottom sx={{ mb: 4, color: "var(--bear-blue)", fontWeight: "bold" }}>
            Barangay Emergency Management Dashboard
          </Typography>

          {/* Live Notifications */}
          <NotificationHistory />

          {/* Stats Cards */}
          <Grid container spacing={3} sx={{ mb: 4 }}>
            {dashboardCards.map((card, index) => (
              <Grid item xs={12} sm={6} md={3} key={index}>
                <Card
                  className="bear-card"
                  sx={{
                    height: "100%",
                    display: "flex",
                    flexDirection: "column",
                    transition: "transform 0.2s ease-in-out",
                    "&:hover": {
                      transform: "translateY(-4px)",
                      boxShadow: 4,
                    },
                  }}
                >
                  <CardContent sx={{ flexGrow: 1, textAlign: "center" }}>
                    <Box
                      sx={{
                        display: "flex",
                        justifyContent: "center",
                        mb: 2,
                        color: card.color,
                      }}
                    >
                      {card.icon}
                    </Box>
                    <Typography variant="h4" component="div" sx={{ fontWeight: "bold", mb: 1 }}>
                      {card.value}
                    </Typography>
                    <Typography variant="h6" component="div" sx={{ mb: 1, color: "var(--bear-blue)", fontWeight: "bold" }}>
                      {card.title}
                    </Typography>
                    <Typography variant="body2" sx={{ color: "var(--bear-black)" }}>
                      {card.description}
                    </Typography>
                  </CardContent>
                </Card>
              </Grid>
            ))}
          </Grid>
        </Box>
      </Box>
    </Box>
  );
}

export default Dashboard;

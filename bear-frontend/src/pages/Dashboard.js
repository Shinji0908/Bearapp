import React, { useState, useEffect } from "react";
import {
  Box,
  Typography,
  Card,
  CardContent,
  Grid,
  AppBar,
  Toolbar,
  Button,
  Avatar,
  Menu,
  MenuItem,
  ListItemIcon,
  ListItemText,
} from "@mui/material";
import {
  AccountCircle,
  ExitToApp,
  Dashboard as DashboardIcon,
  Emergency,
  People,
  Settings,
} from "@mui/icons-material";
import { useNavigate } from "react-router-dom";

function Dashboard() {
  const [user, setUser] = useState(null);
  const [anchorEl, setAnchorEl] = useState(null);
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

  const handleMenuOpen = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleMenuClose = () => {
    setAnchorEl(null);
  };

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

  const displayName = (user.username || `${user.firstName || ""} ${user.lastName || ""}`.trim()).trim();
  const avatarInitial = displayName && displayName.length > 0 ? displayName[0] : "?";

  const dashboardCards = [
    {
      title: "Active Incidents",
      value: "12",
      icon: <Emergency sx={{ fontSize: 40 }} />,
      color: "#f44336",
      description: "Emergency incidents requiring attention",
    },
    {
      title: "Total Residents",
      value: "1,234",
      icon: <People sx={{ fontSize: 40 }} />,
      color: "#2196f3",
      description: "Registered residents in the system",
    },
    {
      title: "Responders",
      value: "45",
      icon: <Settings sx={{ fontSize: 40 }} />,
      color: "#4caf50",
      description: "Active emergency responders",
    },
    {
      title: "Response Time",
      value: "3.2 min",
      icon: <DashboardIcon sx={{ fontSize: 40 }} />,
      color: "#ff9800",
      description: "Average emergency response time",
    },
  ];

  return (
    <Box sx={{ flexGrow: 1 }}>
      {/* Top Navigation */}
      <AppBar position="static" sx={{ backgroundColor: "#2c3e50" }}>
        <Toolbar>
          <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
            🚨 BEAR System Dashboard
          </Typography>
          
          <Typography variant="body2" sx={{ mr: 2 }}>
            Welcome, {displayName}
          </Typography>
          
          <Button
            color="inherit"
            onClick={handleMenuOpen}
            startIcon={<Avatar sx={{ width: 24, height: 24 }}>{avatarInitial}</Avatar>}
          >
            {user.role}
          </Button>
          
          <Menu
            anchorEl={anchorEl}
            open={Boolean(anchorEl)}
            onClose={handleMenuClose}
          >
            <MenuItem onClick={handleMenuClose}>
              <ListItemIcon>
                <AccountCircle fontSize="small" />
              </ListItemIcon>
              <ListItemText>Profile</ListItemText>
            </MenuItem>
            <MenuItem onClick={handleLogout}>
              <ListItemIcon>
                <ExitToApp fontSize="small" />
              </ListItemIcon>
              <ListItemText>Logout</ListItemText>
            </MenuItem>
          </Menu>
        </Toolbar>
      </AppBar>

      {/* Main Content */}
      <Box sx={{ p: 3 }}>
        <Typography variant="h4" gutterBottom sx={{ mb: 4, color: "#2c3e50" }}>
          Emergency Management Dashboard
        </Typography>

        {/* Stats Cards */}
        <Grid container spacing={3} sx={{ mb: 4 }}>
          {dashboardCards.map((card, index) => (
            <Grid item xs={12} sm={6} md={3} key={index}>
              <Card
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
                  <Typography variant="h6" component="div" sx={{ mb: 1, color: "#2c3e50" }}>
                    {card.title}
                  </Typography>
                  <Typography variant="body2" color="text.secondary">
                    {card.description}
                  </Typography>
                </CardContent>
              </Card>
            </Grid>
          ))}
        </Grid>

        {/* Quick Actions */}
        <Card sx={{ mt: 3 }}>
          <CardContent>
            <Typography variant="h6" gutterBottom sx={{ mb: 2 }}>
              Quick Actions
            </Typography>
            <Grid container spacing={2}>
              <Grid item xs={12} sm={6} md={3}>
                <Button
                  variant="contained"
                  fullWidth
                  sx={{ py: 1.5, backgroundColor: "#f44336" }}
                  onClick={() => navigate("/incidents")}
                >
                  View Incidents
                </Button>
              </Grid>
              <Grid item xs={12} sm={6} md={3}>
                <Button
                  variant="contained"
                  fullWidth
                  sx={{ py: 1.5, backgroundColor: "#2196f3" }}
                >
                  Manage Residents
                </Button>
              </Grid>
              <Grid item xs={12} sm={6} md={3}>
                <Button
                  variant="contained"
                  fullWidth
                  sx={{ py: 1.5, backgroundColor: "#4caf50" }}
                >
                  Responders
                </Button>
              </Grid>
              <Grid item xs={12} sm={6} md={3}>
                <Button
                  variant="contained"
                  fullWidth
                  sx={{ py: 1.5, backgroundColor: "#ff9800" }}
                >
                  Reports
                </Button>
              </Grid>
            </Grid>
          </CardContent>
        </Card>
      </Box>
    </Box>
  );
}

export default Dashboard;

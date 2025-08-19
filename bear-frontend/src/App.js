import React from "react";
import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import {
  AppBar,
  Toolbar,
  Typography,
  Box,
  Drawer,
  List,
  ListItem,
  ListItemText,
} from "@mui/material";
import Incidents from "./pages/Incidents";

function App() {
  return (
    <Router>
      {/* Top Navbar */}
      <AppBar position="fixed" sx={{ zIndex: (theme) => theme.zIndex.drawer + 1 }}>
        <Toolbar>
          <Typography variant="h6" noWrap component="div">
            🚨 B.E.A.R Barangay Emergency Alert Response
          </Typography>
        </Toolbar>
      </AppBar>

      <Box sx={{ display: "flex" }}>
        {/* Sidebar */}
        <Drawer
          variant="permanent"
          sx={{
            width: 240,
            flexShrink: 0,
            [`& .MuiDrawer-paper`]: { width: 240, boxSizing: "border-box" },
          }}
        >
          <Toolbar />
          <List>
            <ListItem button component={Link} to="/">
              <ListItemText primary="🏠 Dashboard" />
            </ListItem>
            <ListItem button component={Link} to="/incidents">
              <ListItemText primary="🚨 Incidents" />
            </ListItem>
          </List>
        </Drawer>

        {/* Main content */}
        <Box component="main" sx={{ flexGrow: 1, p: 3 }}>
          <Toolbar />
          <Routes>
            {/* Dashboard (Home) */}
            <Route path="/" element={<h2>Welcome to B.E.A.R System 🚒</h2>} />

            {/* Incidents Page */}
            <Route path="/incidents" element={<Incidents />} />
          </Routes>
        </Box>
      </Box>
    </Router>
  );
}

export default App;

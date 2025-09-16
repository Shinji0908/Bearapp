import React, { useEffect, useState } from "react";
import { 
  Box, 
  List, 
  ListItem, 
  ListItemText, 
  Typography, 
  AppBar, 
  Toolbar, 
  IconButton,
  Chip
} from "@mui/material";
import { ArrowBack, Refresh } from "@mui/icons-material";
import { useNavigate } from "react-router-dom";
import { MapContainer, TileLayer, Marker, Popup, useMap } from "react-leaflet";
import "leaflet/dist/leaflet.css";
import L from "leaflet";

// Fix marker icons (Leaflet bug with Webpack)
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
  iconRetinaUrl: "https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon-2x.png",
  iconUrl: "https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png",
  shadowUrl: "https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png",
});

// Small helper to recenter map
function RecenterMap({ incident }) {
  const map = useMap();
  if (incident?.location) {
    map.setView([incident.location.latitude, incident.location.longitude], 15);
  }
  return null;
}

function Incidents() {
  const [incidents, setIncidents] = useState([]);
  const [selectedIncident, setSelectedIncident] = useState(null);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  const normalizeType = (t) => (typeof t === "string" ? t.toLowerCase() : "barangay");
  const getTypeChipProps = (t) => {
    const type = normalizeType(t);
    switch (type) {
      case "fire":
        return { label: "Fire", color: "error" };
      case "hospital":
        return { label: "Hospital", color: "success" };
      case "police":
        return { label: "Police", color: "info" };
      default:
        return { label: "Barangay", color: "warning" };
    }
  };
  const getTypeEmoji = (t) => {
    const type = normalizeType(t);
    switch (type) {
      case "fire":
        return "🔥";
      case "hospital":
        return "🏥";
      case "police":
        return "🚓";
      default:
        return "🏘️";
    }
  };

  const fetchIncidents = () => {
    setLoading(true);
    fetch("http://localhost:5000/api/incidents")
      .then((res) => res.json())
      .then((data) => {
        // Ensure we always store an array to safely use .map in render
        const normalized = Array.isArray(data)
          ? data
          : Array.isArray(data?.incidents)
          ? data.incidents
          : [];
        if (!Array.isArray(data)) {
          console.warn("Unexpected incidents response shape:", data);
        }
        setIncidents(normalized);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Error fetching incidents:", err);
        setLoading(false);
      });
  };

  useEffect(() => {
    fetchIncidents();
  }, []);

  return (
    <Box sx={{ height: "100vh", display: "flex", flexDirection: "column" }}>
      {/* Header */}
      <AppBar position="static" sx={{ backgroundColor: "#2c3e50" }}>
        <Toolbar>
          <IconButton
            edge="start"
            color="inherit"
            onClick={() => navigate("/dashboard")}
            sx={{ mr: 2 }}
          >
            <ArrowBack />
          </IconButton>
          <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
            🚨 Emergency Incidents
          </Typography>
          <IconButton color="inherit" onClick={fetchIncidents}>
            <Refresh />
          </IconButton>
        </Toolbar>
      </AppBar>

      {/* Main Content */}
      <Box sx={{ display: "flex", flex: 1, height: "calc(100vh - 64px)" }}>
        {/* Left Column - List */}
        <Box
          sx={{
            width: "35%",
            overflowY: "auto",
            borderRight: "1px solid #ddd",
            p: 2,
          }}
        >
          <Typography variant="h6" gutterBottom>
            Reported Incidents ({incidents.length})
          </Typography>
          <List>
            {loading && (
              <Typography variant="body2" color="text.secondary" sx={{ px: 2, py: 1 }}>
                Loading incidents...
              </Typography>
            )}
            {incidents.map((incident, index) => (
              <ListItem
                key={index}
                divider
                button
                onClick={() => setSelectedIncident(incident)}
                sx={{
                  backgroundColor:
                    selectedIncident?._id === incident._id ? "#d1e7ff" : "inherit",
                }}
              >
                <ListItemText
                  primary={`${incident.name} (${incident.contact})`}
                  secondary={
                    <Box sx={{ display: "flex", alignItems: "center", gap: 1 }}>
                      <Chip size="small" variant="filled" {...getTypeChipProps(incident.type)} />
                      <span>{`📍 ${incident.location?.latitude}, ${incident.location?.longitude}`}</span>
                    </Box>
                  }
                />
              </ListItem>
            ))}
          </List>
        </Box>

        {/* Right Column - Map */}
        <Box sx={{ flex: 1 }}>
          <MapContainer
            center={[14.5995, 120.9842]} // Default center (Manila)
            zoom={12}
            style={{ height: "100%", width: "100%" }}
          >
            <TileLayer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" />

            {/* Show all markers */}
            {incidents.map((incident, index) => (
              <Marker
                key={index}
                position={[
                  incident.location?.latitude || 14.5995,
                  incident.location?.longitude || 120.9842,
                ]}
              >
                <Popup>
                  <strong>{getTypeEmoji(incident.type)} {incident.name}</strong>
                  <br />
                  Type: {getTypeChipProps(incident.type).label}
                  <br />
                  📞 {incident.contact}
                </Popup>
              </Marker>
            ))}

            {/* Recenter map when incident is selected */}
            {selectedIncident && <RecenterMap incident={selectedIncident} />}
          </MapContainer>
        </Box>
      </Box>
    </Box>
  );
}

export default Incidents;

import React, { useEffect, useState } from "react";
import { Box, List, ListItem, ListItemText, Typography } from "@mui/material";
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

  useEffect(() => {
    fetch("http://localhost:5000/api/incidents") // ✅ Adjust if backend is deployed
      .then((res) => res.json())
      .then((data) => setIncidents(data))
      .catch((err) => console.error("Error fetching incidents:", err));
  }, []);

  return (
    <Box sx={{ display: "flex", height: "80vh" }}>
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
          🚨 Reported Incidents
        </Typography>
        <List>
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
                secondary={`📍 ${incident.location?.latitude}, ${incident.location?.longitude}`}
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
                <strong>{incident.name}</strong>
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
  );
}

export default Incidents;

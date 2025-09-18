import React from "react";
import { Drawer, List, ListItem, ListItemText } from "@mui/material";
import { Link } from "react-router-dom";

function Sidebar() {
  return (
    <Drawer variant="permanent" anchor="left">
      <List>
        <ListItem button component={Link} to="/residents">
          <ListItemText primary="Residents Info" />
        </ListItem>
        <ListItem button component={Link} to="/responders">
          <ListItemText primary="Responders Info" />
        </ListItem>
        <ListItem button component={Link} to="/incidents">
          <ListItemText primary="Incidents Data" />
        </ListItem>
        <ListItem button component={Link} to="/accounts">
          <ListItemText primary="Manage Accounts" />
        </ListItem>
        <ListItem button component={Link} to="/verify">
          <ListItemText primary="Verify Accounts" />
        </ListItem>
        <ListItem button component={Link} to="/activity">
          <ListItemText primary="Activity Logs" />
        </ListItem>
      </List>
    </Drawer>
  );
}

export default Sidebar;
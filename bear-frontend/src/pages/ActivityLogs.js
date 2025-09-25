import React, { useState, useEffect, useCallback } from "react";
import {
  Box,
  Typography,
  AppBar,
  Toolbar,
  IconButton,
  Card,
  CardContent,
  Grid,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
  TextField,
  InputAdornment,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Chip,
  Alert,
  Snackbar,
  CircularProgress,
  Divider,
  Paper,
  Avatar,
} from "@mui/material";
import {
  ArrowBack,
  Search,
  Refresh,
  History,
  Person,
  Edit,
  Delete,
  Add,
  Verified,
  Cancel,
  Security,
} from "@mui/icons-material";
import { useNavigate } from "react-router-dom";

function ActivityLogs() {
  const [activities, setActivities] = useState([]);
  const [filteredActivities, setFilteredActivities] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState("");
  const [filterAction, setFilterAction] = useState("all");
  const [filterUser, setFilterUser] = useState("all");
  const [snackbar, setSnackbar] = useState({ open: false, message: "", severity: "success" });
  const navigate = useNavigate();

  // Fetch activities data
  const fetchActivities = useCallback(async () => {
    try {
      setLoading(true);
      // In a real app, this would fetch from backend
      // const token = localStorage.getItem("token");
      // const response = await fetch("http://localhost:5000/api/activities", {
      //   headers: { Authorization: `Bearer ${token}` }
      // });
      // const data = await response.json();
      
      // Mock data for demonstration - in real app, this would come from backend
      const mockActivities = [
        {
          id: 1,
          action: "user_created",
          description: "Created new user: John Doe",
          adminName: "Admin User",
          adminId: "admin123",
          targetType: "User",
          targetId: "user456",
          targetName: "John Doe",
          timestamp: new Date(Date.now() - 1000 * 60 * 30), // 30 minutes ago
          details: { role: "Resident", email: "john@example.com" }
        },
        {
          id: 2,
          action: "user_updated",
          description: "Updated user profile: Jane Smith",
          adminName: "Admin User",
          adminId: "admin123",
          targetType: "User",
          targetId: "user789",
          targetName: "Jane Smith",
          timestamp: new Date(Date.now() - 1000 * 60 * 60 * 2), // 2 hours ago
          details: { changes: ["email", "contact"] }
        },
        {
          id: 3,
          action: "user_deleted",
          description: "Deleted user: Bob Johnson",
          adminName: "Admin User",
          adminId: "admin123",
          targetType: "User",
          targetId: "user101",
          targetName: "Bob Johnson",
          timestamp: new Date(Date.now() - 1000 * 60 * 60 * 4), // 4 hours ago
          details: { role: "Resident" }
        },
        {
          id: 4,
          action: "responder_verified",
          description: "Verified responder: Fire Department - Mike Wilson",
          adminName: "Admin User",
          adminId: "admin123",
          targetType: "Responder",
          targetId: "responder202",
          targetName: "Mike Wilson",
          timestamp: new Date(Date.now() - 1000 * 60 * 60 * 6), // 6 hours ago
          details: { responderType: "fire", verificationNotes: "Documents verified" }
        },
        {
          id: 5,
          action: "responder_rejected",
          description: "Rejected responder application: Police - Sarah Davis",
          adminName: "Admin User",
          adminId: "admin123",
          targetType: "Responder",
          targetId: "responder303",
          targetName: "Sarah Davis",
          timestamp: new Date(Date.now() - 1000 * 60 * 60 * 8), // 8 hours ago
          details: { responderType: "police", verificationNotes: "Incomplete documentation" }
        },
        {
          id: 6,
          action: "incident_created",
          description: "Created new incident: Fire Emergency",
          adminName: "Admin User",
          adminId: "admin123",
          targetType: "Incident",
          targetId: "incident404",
          targetName: "Fire Emergency",
          timestamp: new Date(Date.now() - 1000 * 60 * 60 * 12), // 12 hours ago
          details: { type: "fire", location: "123 Main St" }
        },
        {
          id: 7,
          action: "incident_updated",
          description: "Updated incident status: Medical Emergency",
          adminName: "Admin User",
          adminId: "admin123",
          targetType: "Incident",
          targetId: "incident505",
          targetName: "Medical Emergency",
          timestamp: new Date(Date.now() - 1000 * 60 * 60 * 24), // 1 day ago
          details: { statusChange: "open -> resolved" }
        },
        {
          id: 8,
          action: "system_config",
          description: "Updated system configuration",
          adminName: "Admin User",
          adminId: "admin123",
          targetType: "System",
          targetId: "config",
          targetName: "System Settings",
          timestamp: new Date(Date.now() - 1000 * 60 * 60 * 48), // 2 days ago
          details: { setting: "notification_preferences" }
        }
      ];
      
      // For now, use mock data
      setTimeout(() => {
        setActivities(mockActivities);
        setFilteredActivities(mockActivities);
        setLoading(false);
      }, 1000);
    } catch (error) {
      console.error("Error fetching activities:", error);
      showSnackbar(error.message, "error");
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchActivities();
  }, [fetchActivities]);

  // Filter activities based on search and filters
  useEffect(() => {
    let filtered = activities;

    // Filter by search term
    if (searchTerm) {
      filtered = filtered.filter(activity =>
        activity.description?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        activity.adminName?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        activity.targetName?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        activity.action?.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    // Filter by action
    if (filterAction !== "all") {
      filtered = filtered.filter(activity => activity.action === filterAction);
    }

    // Filter by user
    if (filterUser !== "all") {
      filtered = filtered.filter(activity => activity.adminId === filterUser);
    }

    setFilteredActivities(filtered);
  }, [activities, searchTerm, filterAction, filterUser]);

  // Show snackbar
  const showSnackbar = (message, severity) => {
    setSnackbar({ open: true, message, severity });
  };

  // Get action color
  const getActionColor = (action) => {
    switch (action) {
      case "user_created":
      case "responder_verified":
      case "incident_created":
        return "success";
      case "user_updated":
      case "incident_updated":
      case "system_config":
        return "info";
      case "user_deleted":
      case "responder_rejected":
        return "error";
      default:
        return "default";
    }
  };

  // Get action icon
  const getActionIcon = (action) => {
    switch (action) {
      case "user_created":
        return <Add />;
      case "user_updated":
        return <Edit />;
      case "user_deleted":
        return <Delete />;
      case "responder_verified":
        return <Verified />;
      case "responder_rejected":
        return <Cancel />;
      case "incident_created":
      case "incident_updated":
        return <Security />;
      case "system_config":
        return <History />;
      default:
        return <Person />;
    }
  };

  // Get action label
  const getActionLabel = (action) => {
    switch (action) {
      case "user_created": return "User Created";
      case "user_updated": return "User Updated";
      case "user_deleted": return "User Deleted";
      case "responder_verified": return "Responder Verified";
      case "responder_rejected": return "Responder Rejected";
      case "incident_created": return "Incident Created";
      case "incident_updated": return "Incident Updated";
      case "system_config": return "System Config";
      default: return action;
    }
  };

  // Format timestamp
  const formatTimestamp = (timestamp) => {
    const now = new Date();
    const diff = now - timestamp;
    const minutes = Math.floor(diff / (1000 * 60));
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));

    if (minutes < 60) {
      return `${minutes} minutes ago`;
    } else if (hours < 24) {
      return `${hours} hours ago`;
    } else {
      return `${days} days ago`;
    }
  };

  // Get unique admins for filter
  const uniqueAdmins = [...new Set(activities.map(activity => activity.adminId))];

  if (loading) {
    return (
      <Box sx={{ display: "flex", justifyContent: "center", alignItems: "center", height: "100vh" }}>
        <CircularProgress />
      </Box>
    );
  }

  // Check if user has admin access
  const currentUser = JSON.parse(localStorage.getItem("user") || "{}");
  if (currentUser.role !== "Admin") {
    return (
      <Box sx={{ height: "100vh", display: "flex", flexDirection: "column" }}>
        <AppBar position="static" sx={{ backgroundColor: "var(--bear-dark-red)", borderRadius: 0 }}>
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
              📋 Activity Logs
            </Typography>
          </Toolbar>
        </AppBar>
        <Box sx={{ p: 3, textAlign: "center" }}>
          <Typography variant="h5" color="error" gutterBottom>
            Access Denied
          </Typography>
          <Typography variant="body1" color="text.secondary">
            You need Admin privileges to access the activity logs.
          </Typography>
        </Box>
      </Box>
    );
  }

  return (
    <Box sx={{ height: "100vh", display: "flex", flexDirection: "column" }}>
      {/* Header */}
      <AppBar position="static" sx={{ backgroundColor: "var(--bear-dark-red)", borderRadius: 0 }}>
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
            📋 Activity Logs
          </Typography>
          <IconButton color="inherit" onClick={fetchActivities}>
            <Refresh />
          </IconButton>
        </Toolbar>
      </AppBar>

      {/* Main Content */}
      <Box sx={{ p: 3, flex: 1, overflow: "hidden" }}>
        {/* Stats Cards */}
        <Grid container spacing={3} sx={{ mb: 3 }}>
          <Grid item xs={12} sm={6} md={3}>
            <Card>
              <CardContent sx={{ textAlign: "center" }}>
                <Typography variant="h4" color="primary">
                  {activities.length}
                </Typography>
                <Typography variant="body2" color="text.secondary">
                  Total Activities
                </Typography>
              </CardContent>
            </Card>
          </Grid>
          <Grid item xs={12} sm={6} md={3}>
            <Card>
              <CardContent sx={{ textAlign: "center" }}>
                <Typography variant="h4" color="success.main">
                  {activities.filter(a => a.action.includes("created") || a.action.includes("verified")).length}
                </Typography>
                <Typography variant="body2" color="text.secondary">
                  Create/Verify Actions
                </Typography>
              </CardContent>
            </Card>
          </Grid>
          <Grid item xs={12} sm={6} md={3}>
            <Card>
              <CardContent sx={{ textAlign: "center" }}>
                <Typography variant="h4" color="info.main">
                  {activities.filter(a => a.action.includes("updated")).length}
                </Typography>
                <Typography variant="body2" color="text.secondary">
                  Update Actions
                </Typography>
              </CardContent>
            </Card>
          </Grid>
          <Grid item xs={12} sm={6} md={3}>
            <Card>
              <CardContent sx={{ textAlign: "center" }}>
                <Typography variant="h4" color="error.main">
                  {activities.filter(a => a.action.includes("deleted") || a.action.includes("rejected")).length}
                </Typography>
                <Typography variant="body2" color="text.secondary">
                  Delete/Reject Actions
                </Typography>
              </CardContent>
            </Card>
          </Grid>
        </Grid>

        {/* Filters */}
        <Card sx={{ mb: 3 }}>
          <CardContent>
            <Grid container spacing={2} alignItems="center">
              <Grid item xs={12} md={4}>
                <TextField
                  fullWidth
                  placeholder="Search activities..."
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  InputProps={{
                    startAdornment: (
                      <InputAdornment position="start">
                        <Search />
                      </InputAdornment>
                    ),
                  }}
                />
              </Grid>
              <Grid item xs={12} md={4}>
                <FormControl fullWidth>
                  <InputLabel>Filter by Action</InputLabel>
                  <Select
                    value={filterAction}
                    onChange={(e) => setFilterAction(e.target.value)}
                    label="Filter by Action"
                  >
                    <MenuItem value="all">All Actions</MenuItem>
                    <MenuItem value="user_created">User Created</MenuItem>
                    <MenuItem value="user_updated">User Updated</MenuItem>
                    <MenuItem value="user_deleted">User Deleted</MenuItem>
                    <MenuItem value="responder_verified">Responder Verified</MenuItem>
                    <MenuItem value="responder_rejected">Responder Rejected</MenuItem>
                    <MenuItem value="incident_created">Incident Created</MenuItem>
                    <MenuItem value="incident_updated">Incident Updated</MenuItem>
                    <MenuItem value="system_config">System Config</MenuItem>
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12} md={4}>
                <FormControl fullWidth>
                  <InputLabel>Filter by Admin</InputLabel>
                  <Select
                    value={filterUser}
                    onChange={(e) => setFilterUser(e.target.value)}
                    label="Filter by Admin"
                  >
                    <MenuItem value="all">All Admins</MenuItem>
                    {uniqueAdmins.map(adminId => (
                      <MenuItem key={adminId} value={adminId}>
                        {activities.find(a => a.adminId === adminId)?.adminName || adminId}
                      </MenuItem>
                    ))}
                  </Select>
                </FormControl>
              </Grid>
            </Grid>
          </CardContent>
        </Card>

        {/* Activities Table */}
        <Card sx={{ flex: 1, overflow: "hidden" }}>
          <CardContent>
            <Box sx={{ display: "flex", alignItems: "center", mb: 2 }}>
              <History sx={{ mr: 1 }} />
              <Typography variant="h6">
                Admin Activities ({filteredActivities.length} activities)
              </Typography>
            </Box>
            <Divider sx={{ mb: 2 }} />
            <TableContainer sx={{ maxHeight: 500 }}>
              <Table stickyHeader>
                <TableHead>
                  <TableRow>
                    <TableCell>Action</TableCell>
                    <TableCell>Description</TableCell>
                    <TableCell>Admin</TableCell>
                    <TableCell>Target</TableCell>
                    <TableCell>Timestamp</TableCell>
                    <TableCell>Details</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {filteredActivities.map((activity) => (
                    <TableRow key={activity.id} hover>
                      <TableCell>
                        <Box sx={{ display: "flex", alignItems: "center", gap: 1 }}>
                          <Avatar sx={{ bgcolor: `${getActionColor(activity.action)}.main`, width: 32, height: 32 }}>
                            {getActionIcon(activity.action)}
                          </Avatar>
                          <Chip
                            label={getActionLabel(activity.action)}
                            color={getActionColor(activity.action)}
                            size="small"
                          />
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Typography variant="body2">
                          {activity.description}
                        </Typography>
                      </TableCell>
                      <TableCell>
                        <Box sx={{ display: "flex", alignItems: "center", gap: 1 }}>
                          <Avatar sx={{ bgcolor: "primary.main", width: 24, height: 24 }}>
                            {activity.adminName?.[0] || "A"}
                          </Avatar>
                          <Typography variant="body2">
                            {activity.adminName}
                          </Typography>
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Typography variant="body2">
                          {activity.targetType}: {activity.targetName}
                        </Typography>
                      </TableCell>
                      <TableCell>
                        <Typography variant="body2" color="text.secondary">
                          {formatTimestamp(activity.timestamp)}
                        </Typography>
                        <Typography variant="caption" color="text.secondary">
                          {activity.timestamp.toLocaleString()}
                        </Typography>
                      </TableCell>
                      <TableCell>
                        {activity.details && (
                          <Paper sx={{ p: 1, bgcolor: "grey.50" }}>
                            <Typography variant="caption" color="text.secondary">
                              {JSON.stringify(activity.details, null, 2)}
                            </Typography>
                          </Paper>
                        )}
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </TableContainer>
          </CardContent>
        </Card>
      </Box>

      {/* Snackbar */}
      <Snackbar
        open={snackbar.open}
        autoHideDuration={6000}
        onClose={() => setSnackbar({ ...snackbar, open: false })}
      >
        <Alert
          onClose={() => setSnackbar({ ...snackbar, open: false })}
          severity={snackbar.severity}
          sx={{ width: "100%" }}
        >
          {snackbar.message}
        </Alert>
      </Snackbar>
    </Box>
  );
}

export default ActivityLogs;

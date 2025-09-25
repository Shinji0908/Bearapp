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
  Button,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Chip,
  TextField,
  InputAdornment,
  Alert,
  Snackbar,
  CircularProgress,
  Divider,
} from "@mui/material";
import {
  ArrowBack,
  Search,
  Download,
  Refresh,
  Assessment,
} from "@mui/icons-material";
import { useNavigate } from "react-router-dom";

function Reports() {
  const [incidents, setIncidents] = useState([]);
  const [filteredIncidents, setFilteredIncidents] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState("");
  const [filterType, setFilterType] = useState("all");
  const [filterPeriod, setFilterPeriod] = useState("month");
  const [filterDate, setFilterDate] = useState(new Date().toISOString().slice(0, 7)); // YYYY-MM format
  const [snackbar, setSnackbar] = useState({ open: false, message: "", severity: "success" });
  const navigate = useNavigate();

  // Fetch incidents data
  const fetchIncidents = useCallback(async () => {
    try {
      setLoading(true);
      const token = localStorage.getItem("token");
      
      const response = await fetch("http://localhost:5000/api/incidents", {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      
      if (response.ok) {
        const data = await response.json();
        setIncidents(data);
        setFilteredIncidents(data);
      } else {
        throw new Error("Failed to fetch incidents");
      }
    } catch (error) {
      console.error("Error fetching incidents:", error);
      showSnackbar(error.message, "error");
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchIncidents();
  }, [fetchIncidents]);

  // Filter incidents based on search, type, and date
  useEffect(() => {
    let filtered = incidents;

    // Filter by search term
    if (searchTerm) {
      filtered = filtered.filter(incident => {
        const locationString = typeof incident.location === 'object' && incident.location !== null
          ? `${incident.location.latitude || ''}, ${incident.location.longitude || ''}`
          : incident.location || '';
        
        return incident.title?.toLowerCase().includes(searchTerm.toLowerCase()) ||
               incident.description?.toLowerCase().includes(searchTerm.toLowerCase()) ||
               locationString.toLowerCase().includes(searchTerm.toLowerCase()) ||
               incident.type?.toLowerCase().includes(searchTerm.toLowerCase());
      });
    }

    // Filter by type
    if (filterType !== "all") {
      filtered = filtered.filter(incident => incident.type === filterType);
    }

    // Filter by date period
    if (filterPeriod === "month") {
      const selectedYear = filterDate.split("-")[0];
      const selectedMonth = filterDate.split("-")[1];
      filtered = filtered.filter(incident => {
        const incidentDate = new Date(incident.createdAt);
        return incidentDate.getFullYear() === parseInt(selectedYear) && 
               (incidentDate.getMonth() + 1) === parseInt(selectedMonth);
      });
    } else if (filterPeriod === "year") {
      const selectedYear = filterDate.split("-")[0];
      filtered = filtered.filter(incident => {
        const incidentDate = new Date(incident.createdAt);
        return incidentDate.getFullYear() === parseInt(selectedYear);
      });
    }

    setFilteredIncidents(filtered);
  }, [incidents, searchTerm, filterType, filterPeriod, filterDate]);

  // Show snackbar
  const showSnackbar = (message, severity) => {
    setSnackbar({ open: true, message, severity });
  };

  // Get status color
  const getStatusColor = (status) => {
    switch (status) {
      case "open": return "error";
      case "in_progress": return "warning";
      case "resolved": return "success";
      case "closed": return "default";
      default: return "default";
    }
  };

  // Get type color
  const getTypeColor = (type) => {
    switch (type) {
      case "fire": return "error";
      case "medical": return "success";
      case "police": return "info";
      case "other": return "default";
      default: return "default";
    }
  };

  // Format date
  const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    return new Date(dateString).toLocaleDateString();
  };

  // Format time
  const formatTime = (dateString) => {
    if (!dateString) return "N/A";
    return new Date(dateString).toLocaleTimeString();
  };

  // Generate report data
  const generateReportData = () => {
    const totalIncidents = filteredIncidents.length;
    const openIncidents = filteredIncidents.filter(i => i.status === "open").length;
    const inProgressIncidents = filteredIncidents.filter(i => i.status === "in_progress").length;
    const resolvedIncidents = filteredIncidents.filter(i => i.status === "resolved").length;
    const closedIncidents = filteredIncidents.filter(i => i.status === "closed").length;

    const typeBreakdown = filteredIncidents.reduce((acc, incident) => {
      acc[incident.type] = (acc[incident.type] || 0) + 1;
      return acc;
    }, {});

    return {
      totalIncidents,
      openIncidents,
      inProgressIncidents,
      resolvedIncidents,
      closedIncidents,
      typeBreakdown,
      period: filterPeriod === "month" ? 
        new Date(filterDate + "-01").toLocaleDateString("en-US", { month: "long", year: "numeric" }) :
        filterDate.split("-")[0]
    };
  };

  // Download report as CSV
  const downloadReport = () => {
    const reportData = generateReportData();
    const csvContent = [
      ["Incident Report", `Period: ${reportData.period}`],
      ["Generated on", new Date().toLocaleString()],
      [""],
      ["Summary"],
      ["Total Incidents", reportData.totalIncidents],
      ["Open", reportData.openIncidents],
      ["In Progress", reportData.inProgressIncidents],
      ["Resolved", reportData.resolvedIncidents],
      ["Closed", reportData.closedIncidents],
      [""],
      ["Incident Details"],
      ["Title", "Type", "Status", "Location", "Date", "Time", "Description"]
    ];

    // Add incident details
    filteredIncidents.forEach(incident => {
      csvContent.push([
        incident.title || "",
        incident.type || "",
        incident.status || "",
        incident.location || "",
        formatDate(incident.createdAt),
        formatTime(incident.createdAt),
        (incident.description || "").replace(/\n/g, " ")
      ]);
    });

    const csvString = csvContent.map(row => 
      row.map(field => `"${field}"`).join(",")
    ).join("\n");

    const blob = new Blob([csvString], { type: "text/csv" });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `incident-report-${reportData.period.replace(/\s+/g, "-").toLowerCase()}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);

    showSnackbar("Report downloaded successfully", "success");
  };

  if (loading) {
    return (
      <Box sx={{ display: "flex", justifyContent: "center", alignItems: "center", height: "100vh" }}>
        <CircularProgress />
      </Box>
    );
  }

  const reportData = generateReportData();

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
            📊 Incident Reports
          </Typography>
          <IconButton color="inherit" onClick={fetchIncidents}>
            <Refresh />
          </IconButton>
        </Toolbar>
      </AppBar>

      {/* Main Content */}
      <Box sx={{ p: 3, flex: 1, overflow: "hidden" }}>
        {/* Report Summary */}
        <Grid container spacing={3} sx={{ mb: 3 }}>
          <Grid item xs={12} sm={6} md={2.4}>
            <Card>
              <CardContent sx={{ textAlign: "center" }}>
                <Typography variant="h4" color="primary">
                  {reportData.totalIncidents}
                </Typography>
                <Typography variant="body2" color="text.secondary">
                  Total Incidents
                </Typography>
              </CardContent>
            </Card>
          </Grid>
          <Grid item xs={12} sm={6} md={2.4}>
            <Card>
              <CardContent sx={{ textAlign: "center" }}>
                <Typography variant="h4" color="error.main">
                  {reportData.openIncidents}
                </Typography>
                <Typography variant="body2" color="text.secondary">
                  Open
                </Typography>
              </CardContent>
            </Card>
          </Grid>
          <Grid item xs={12} sm={6} md={2.4}>
            <Card>
              <CardContent sx={{ textAlign: "center" }}>
                <Typography variant="h4" color="warning.main">
                  {reportData.inProgressIncidents}
                </Typography>
                <Typography variant="body2" color="text.secondary">
                  In Progress
                </Typography>
              </CardContent>
            </Card>
          </Grid>
          <Grid item xs={12} sm={6} md={2.4}>
            <Card>
              <CardContent sx={{ textAlign: "center" }}>
                <Typography variant="h4" color="success.main">
                  {reportData.resolvedIncidents}
                </Typography>
                <Typography variant="body2" color="text.secondary">
                  Resolved
                </Typography>
              </CardContent>
            </Card>
          </Grid>
          <Grid item xs={12} sm={6} md={2.4}>
            <Card>
              <CardContent sx={{ textAlign: "center" }}>
                <Typography variant="h4" color="text.secondary">
                  {reportData.closedIncidents}
                </Typography>
                <Typography variant="body2" color="text.secondary">
                  Closed
                </Typography>
              </CardContent>
            </Card>
          </Grid>
        </Grid>

        {/* Filters and Controls */}
        <Card sx={{ mb: 3 }}>
          <CardContent>
            <Grid container spacing={2} alignItems="center">
              <Grid item xs={12} md={3}>
                <TextField
                  fullWidth
                  placeholder="Search incidents..."
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
              <Grid item xs={12} md={2}>
                <FormControl fullWidth>
                  <InputLabel>Type</InputLabel>
                  <Select
                    value={filterType}
                    onChange={(e) => setFilterType(e.target.value)}
                    label="Type"
                  >
                    <MenuItem value="all">All Types</MenuItem>
                    <MenuItem value="fire">Fire</MenuItem>
                    <MenuItem value="medical">Medical</MenuItem>
                    <MenuItem value="police">Police</MenuItem>
                    <MenuItem value="other">Other</MenuItem>
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12} md={2}>
                <FormControl fullWidth>
                  <InputLabel>Period</InputLabel>
                  <Select
                    value={filterPeriod}
                    onChange={(e) => setFilterPeriod(e.target.value)}
                    label="Period"
                  >
                    <MenuItem value="month">Month</MenuItem>
                    <MenuItem value="year">Year</MenuItem>
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12} md={2}>
                <TextField
                  fullWidth
                  type={filterPeriod === "month" ? "month" : "number"}
                  label={filterPeriod === "month" ? "Select Month" : "Select Year"}
                  value={filterDate}
                  onChange={(e) => setFilterDate(e.target.value)}
                  InputLabelProps={{ shrink: true }}
                />
              </Grid>
              <Grid item xs={12} md={3}>
                <Button
                  variant="contained"
                  startIcon={<Download />}
                  onClick={downloadReport}
                  fullWidth
                  sx={{ py: 1.5 }}
                >
                  Download Report
                </Button>
              </Grid>
            </Grid>
          </CardContent>
        </Card>

        {/* Incidents Table */}
        <Card sx={{ flex: 1, overflow: "hidden" }}>
          <CardContent>
            <Box sx={{ display: "flex", alignItems: "center", mb: 2 }}>
              <Assessment sx={{ mr: 1 }} />
              <Typography variant="h6">
                Incident Details ({filteredIncidents.length} incidents)
              </Typography>
            </Box>
            <Divider sx={{ mb: 2 }} />
            <TableContainer sx={{ maxHeight: 400 }}>
              <Table stickyHeader>
                <TableHead>
                  <TableRow>
                    <TableCell>Title</TableCell>
                    <TableCell>Type</TableCell>
                    <TableCell>Status</TableCell>
                    <TableCell>Location</TableCell>
                    <TableCell>Date</TableCell>
                    <TableCell>Time</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {filteredIncidents.map((incident) => (
                    <TableRow key={incident._id} hover>
                      <TableCell>
                        <Typography variant="subtitle2">
                          {incident.title || "Untitled"}
                        </Typography>
                        <Typography variant="body2" color="text.secondary" sx={{ mt: 0.5 }}>
                          {incident.description?.substring(0, 50)}
                          {incident.description?.length > 50 ? "..." : ""}
                        </Typography>
                      </TableCell>
                      <TableCell>
                        <Chip
                          label={incident.type || "Unknown"}
                          color={getTypeColor(incident.type)}
                          size="small"
                        />
                      </TableCell>
                      <TableCell>
                        <Chip
                          label={incident.status || "Unknown"}
                          color={getStatusColor(incident.status)}
                          size="small"
                        />
                      </TableCell>
                      <TableCell>
                        <Typography variant="body2">
                          {typeof incident.location === 'object' && incident.location !== null
                            ? `${incident.location.latitude || 'N/A'}, ${incident.location.longitude || 'N/A'}`
                            : incident.location || "N/A"}
                        </Typography>
                      </TableCell>
                      <TableCell>
                        <Typography variant="body2">
                          {formatDate(incident.createdAt)}
                        </Typography>
                      </TableCell>
                      <TableCell>
                        <Typography variant="body2">
                          {formatTime(incident.createdAt)}
                        </Typography>
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

export default Reports;

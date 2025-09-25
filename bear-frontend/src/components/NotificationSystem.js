import React, { useState, useEffect, useCallback } from 'react';
import { 
  Snackbar, 
  Alert, 
  AlertTitle, 
  IconButton, 
  Typography, 
  Box,
  Chip
} from '@mui/material';
import { 
  Close, 
  Emergency, 
  LocalFireDepartment, 
  LocalHospital, 
  LocalPolice, 
  Home 
} from '@mui/icons-material';

function NotificationSystem({ socket }) {
  const [, setNotifications] = useState([]);
  const [currentNotification, setCurrentNotification] = useState(null);

  const getTypeIcon = (type) => {
    switch (type?.toLowerCase()) {
      case 'fire':
        return <LocalFireDepartment sx={{ fontSize: 20 }} />;
      case 'hospital':
        return <LocalHospital sx={{ fontSize: 20 }} />;
      case 'police':
        return <LocalPolice sx={{ fontSize: 20 }} />;
      default:
        return <Home sx={{ fontSize: 20 }} />;
    }
  };

  const getTypeColor = (type) => {
    switch (type?.toLowerCase()) {
      case 'fire':
        return 'error';
      case 'hospital':
        return 'success';
      case 'police':
        return 'info';
      default:
        return 'warning';
    }
  };

  const playNotificationSound = () => {
    // Create a simple notification sound
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
    oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
    oscillator.frequency.setValueAtTime(800, audioContext.currentTime + 0.2);
    
    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.5);
  };

  const showNotification = useCallback((incident) => {
    const notification = {
      id: Date.now(),
      incident,
      timestamp: new Date()
    };
    
    setNotifications(prev => [notification, ...prev.slice(0, 4)]); // Keep last 5
    setCurrentNotification(notification);
    
    // Play sound
    playNotificationSound();
    
    // Auto-hide after 8 seconds
    setTimeout(() => {
      setCurrentNotification(null);
    }, 8000);
  }, []);

  const showStatusUpdateNotification = useCallback((incident) => {
    const notification = {
      id: Date.now(),
      incident,
      timestamp: new Date(),
      isStatusUpdate: true
    };
    
    setNotifications(prev => [notification, ...prev.slice(0, 4)]); // Keep last 5
    setCurrentNotification(notification);
    
    // Play sound for status updates too
    playNotificationSound();
    
    // Auto-hide after 6 seconds (shorter for status updates)
    setTimeout(() => {
      setCurrentNotification(null);
    }, 6000);
  }, []);

  useEffect(() => {
    if (!socket) return;

    // Listen for new incidents
    socket.on('incidentCreated', ({ incident }) => {
      showNotification(incident);
    });

    // Listen for status updates
    socket.on('incidentStatusUpdated', ({ incident }) => {
      showStatusUpdateNotification(incident);
    });

    return () => {
      socket.off('incidentCreated');
      socket.off('incidentStatusUpdated');
    };
  }, [socket, showNotification, showStatusUpdateNotification]);

  const handleClose = () => {
    setCurrentNotification(null);
  };

  const handleNotificationClick = () => {
    // You can add navigation to the incident here
    console.log('Notification clicked:', currentNotification?.incident);
    setCurrentNotification(null);
  };

  if (!currentNotification) return null;

  const { incident } = currentNotification;
  
  const reporterName = incident.reportedBy 
    ? `${incident.reportedBy.firstName || 'Unknown'} ${incident.reportedBy.lastName || 'User'}`
    : incident.name || 'Anonymous User';

  return (
    <Snackbar
      open={!!currentNotification}
      autoHideDuration={8000}
      onClose={handleClose}
      anchorOrigin={{ vertical: 'top', horizontal: 'right' }}
      sx={{ mt: 8 }}
    >
      <Alert
        severity={getTypeColor(incident.type)}
        variant="filled"
        icon={getTypeIcon(incident.type)}
        action={
          <IconButton
            size="small"
            aria-label="close"
            color="inherit"
            onClick={handleClose}
          >
            <Close fontSize="small" />
          </IconButton>
        }
        onClick={handleNotificationClick}
        sx={{ 
          cursor: 'pointer',
          minWidth: 350,
          '&:hover': {
            transform: 'scale(1.02)',
            transition: 'transform 0.2s ease-in-out'
          }
        }}
      >
        <AlertTitle sx={{ display: 'flex', alignItems: 'center', gap: 1 }}>
          <Emergency sx={{ fontSize: 18 }} />
          {currentNotification.isStatusUpdate ? 'Incident Status Updated' : 'New Emergency Incident'}
        </AlertTitle>
        
        <Box sx={{ mt: 1 }}>
          <Typography variant="body2" sx={{ fontWeight: 'bold', mb: 0.5 }}>
            {incident.name}
          </Typography>
          
          {incident.description && (
            <Typography variant="body2" sx={{ mb: 1, opacity: 0.9 }}>
              {incident.description.length > 100 
                ? incident.description.substring(0, 100) + '...' 
                : incident.description
              }
            </Typography>
          )}
          
          <Box sx={{ display: 'flex', alignItems: 'center', gap: 1, flexWrap: 'wrap' }}>
            <Chip 
              size="small" 
              label={incident.type?.charAt(0).toUpperCase() + incident.type?.slice(1)} 
              color={getTypeColor(incident.type)}
              variant="outlined"
              sx={{ color: 'white', borderColor: 'white' }}
            />
            <Typography variant="caption" sx={{ opacity: 0.8 }}>
              👤 {reporterName}
            </Typography>
            <Typography variant="caption" sx={{ opacity: 0.8 }}>
              📍 {incident.location?.latitude?.toFixed(4)}, {incident.location?.longitude?.toFixed(4)}
            </Typography>
            {currentNotification.isStatusUpdate && (
              <Typography variant="caption" sx={{ opacity: 0.8, fontWeight: 'bold' }}>
                📊 Status: {incident.status}
              </Typography>
            )}
          </Box>
        </Box>
      </Alert>
    </Snackbar>
  );
}

export default NotificationSystem;

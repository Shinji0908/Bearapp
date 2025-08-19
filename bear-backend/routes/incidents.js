const express = require('express');
const router = express.Router();
const Incident = require('../models/Incident');

// POST — Save incident
router.post("/", async (req, res) => {
    console.log("📩 Incoming data:", req.body); // Log the incoming request

    try {
        const incident = new Incident(req.body);
        await incident.save();
        res.status(201).json({ message: "Incident reported successfully" });
    } catch (error) {
        console.error("❌ Save error:", error);
        res.status(500).json({ message: "Failed to report incident" });
    }
});

// GET — Get all incidents
router.get('/', async (req, res) => {
    try {
        const incidents = await Incident.find();
        res.json(incidents);
    } catch (err) {
        console.error('❌ Error:', err);
        res.status(500).json({ error: err.message });
    }
});

module.exports = router;

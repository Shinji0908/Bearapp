const express = require("express");
const router = express.Router();
const jwt = require("jsonwebtoken");
const Incident = require("../models/Incident");
const JWT_SECRET = process.env.JWT_SECRET || "supersecretkey";

/**
 * @route   POST /api/incidents
 * @desc    Report a new incident (Resident/Responder must be logged in)
 */
router.post("/", async (req, res) => {
  try {
    // ✅ Verify token
    const token = req.headers.authorization?.split(" ")[1];
    if (!token) return res.status(401).json({ message: "No token provided" });

    const decoded = jwt.verify(token, JWT_SECRET);

    // ✅ Create new incident linked to user
    const { name, contact, location, type } = req.body;
    if (!name || !contact || !location?.latitude || !location?.longitude) {
      return res.status(400).json({ message: "name, contact and location(lat, lng) are required" });
    }

    // Normalize and validate type
    const allowedTypes = ["barangay", "fire", "hospital", "police"];
    const normalizedType = typeof type === "string" ? type.toLowerCase() : undefined;
    const finalType = allowedTypes.includes(normalizedType) ? normalizedType : undefined; // model default applies

    const incident = new Incident({
      name,
      contact,
      location,
      type: finalType,
      reportedBy: decoded.id,
    });

    await incident.save();
    res.status(201).json({ message: "Incident reported successfully", incident });
  } catch (error) {
    console.error("❌ Save error:", error);
    res.status(500).json({ message: "Failed to report incident" });
  }
});

/**
 * @route   GET /api/incidents
 * @desc    Get all incidents with user info
 */
router.get("/", async (req, res) => {
  try {
    const incidents = await Incident.find().populate({
      path: "reportedBy",
      select: "firstName lastName email role",
      strictPopulate: false,
    }).lean();
    res.json(incidents);
  } catch (err) {
    console.error("❌ Error:", err);
    res.status(500).json({ error: err.message });
  }
});

module.exports = router;

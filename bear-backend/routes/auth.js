const express = require("express");
const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");
const User = require("../models/User");

const router = express.Router();
const JWT_SECRET = process.env.JWT_SECRET || "supersecretkey"; // 🔐 move this to .env

/**
 * @route   POST /api/auth/register
 * @desc    Register a new user (Resident or Responder)
 */
router.post("/register", async (req, res) => {
  try {
    console.log("🔍 Register endpoint hit - Method:", req.method, "URL:", req.url);
    console.log("🔍 Headers:", req.headers);
    console.log("🔍 Body:", req.body);
    
    const { firstName, lastName, username, email, contact, password, role, responderType, birthday } = req.body;

    // ✅ Validate required fields
    if (!firstName || !lastName || !email || !password || !role) {
      return res.status(400).json({ 
        message: "Missing required fields: firstName, lastName, email, password, role are required" 
      });
    }

    // ✅ Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return res.status(400).json({ message: "Invalid email format" });
    }

    // ✅ Validate role
    const validRoles = ["Resident", "Responder"];
    if (!validRoles.includes(role)) {
      return res.status(400).json({ 
        message: "Invalid role. Must be 'Resident' or 'Responder'" 
      });
    }

    // ✅ If role is Responder, validate responderType
    if (role === "Responder" && !responderType) {
      return res.status(400).json({ 
        message: "responderType is required when role is 'Responder'" 
      });
    }

    // ✅ Generate username if not provided
    let finalUsername = username || `${firstName.toLowerCase()}_${lastName.toLowerCase()}_${Date.now()}`;

    // ✅ Check if email already exists
    const existingUserByEmail = await User.findOne({ email });
    if (existingUserByEmail) {
      return res.status(400).json({ message: "Email already registered" });
    }

    // ✅ Check username uniqueness
    const existingUserByUsername = await User.findOne({ username: finalUsername });
    if (existingUserByUsername) {
      finalUsername = `${finalUsername}_${Date.now()}`;
    }

    // ✅ Hash password
    const hashedPassword = await bcrypt.hash(password, 10);

    // ✅ Create user
    const newUser = new User({
      firstName,
      lastName,
      username: finalUsername,
      email,
      contact: contact || null,
      password: hashedPassword,
      role,
      responderType: role === "Responder" ? responderType : null,
      birthday: birthday || null,
    });

    await newUser.save();

    res.status(201).json({
      message: "User registered successfully",
      user: {
        id: newUser._id,
        firstName: newUser.firstName,
        lastName: newUser.lastName,
        username: newUser.username,
        email: newUser.email,
        role: newUser.role,
        responderType: newUser.responderType,
        birthday: newUser.birthday,
      },
    });
  } catch (err) {
    console.error("❌ Register Error:", err);
    if (err.code === 11000) {
      return res.status(400).json({ message: "Username or email already exists" });
    }
    res.status(500).json({ message: "Server error" });
  }
});

/**
 * @route   POST /api/auth/login
 * @desc    Login for Residents & Responders
 */
router.post("/login", async (req, res) => {
  try {
    const { email, password } = req.body;

    const user = await User.findOne({ email });
    if (!user) return res.status(401).json({ message: "Invalid credentials" });

    const isMatch = await bcrypt.compare(password, user.password);
    if (!isMatch) return res.status(401).json({ message: "Invalid credentials" });

    // ✅ Generate JWT
    const token = jwt.sign(
      { id: user._id, role: user.role, responderType: user.responderType },
      JWT_SECRET,
      { expiresIn: "7d" }
    );

    res.json({
      message: "Login successful",
      token,
      user: {
        id: user._id,
        firstName: user.firstName,
        lastName: user.lastName,
        email: user.email,
        contact: user.contact,
        role: user.role,
        responderType: user.responderType,
      },
    });
  } catch (err) {
    console.error("❌ Login Error:", err);
    res.status(500).json({ message: "Server error" });
  }
});

/**
 * @route   GET /api/auth/profile
 * @desc    Get current user's profile (protected)
 */
router.get("/profile", async (req, res) => {
  try {
    console.log("🔍 Profile endpoint hit - Method:", req.method, "URL:", req.url);
    console.log("🔍 Headers:", req.headers);
    
    const authHeader = req.headers.authorization;
    console.log("🔍 Authorization header:", authHeader);
    
    if (!authHeader) {
      console.log("❌ No authorization header provided");
      return res.status(401).json({ message: "No token provided" });
    }
    
    const token = authHeader.split(" ")[1];
    if (!token) {
      console.log("❌ No token found in authorization header");
      return res.status(401).json({ message: "No token provided" });
    }

    const decoded = jwt.verify(token, JWT_SECRET);
    const user = await User.findById(decoded.id).select("-password");

    res.json(user);
  } catch (err) {
    console.error("❌ Profile Error:", err);
    res.status(500).json({ message: "Invalid or expired token" });
  }
});

module.exports = router;

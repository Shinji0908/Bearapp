const express = require("express");
const router = express.Router();
const User = require("../models/User");
const bcrypt = require("bcrypt");

// Register
router.post("/register", async (req, res) => {
  try {
    const hashedPassword = await bcrypt.hash(req.body.password, 10);
    const newUser = new User({
      username: req.body.username,
      password: hashedPassword,
      role: req.body.role || "Responder",
    });
    await newUser.save();
    res.status(201).json({ message: "✅ User registered successfully" });
  } catch (err) {
    res.status(500).json({ message: "❌ Error registering user", error: err });
  }
});

// Login
router.post("/login", async (req, res) => {
  try {
    const user = await User.findOne({ username: req.body.username });
    if (!user) return res.status(400).json({ message: "❌ User not found" });

    const validPassword = await bcrypt.compare(req.body.password, user.password);
    if (!validPassword) return res.status(400).json({ message: "❌ Invalid password" });

    res.json({ message: "✅ Login successful", user: { id: user._id, role: user.role } });
  } catch (err) {
    res.status(500).json({ message: "❌ Error logging in", error: err });
  }
});

module.exports = router;

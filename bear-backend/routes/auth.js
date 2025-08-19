const express = require('express');
const router = express.Router();
const User = require('../models/User');

// POST /api/auth/register
router.post('/register', async (req, res) => {
  try {
    console.log('POST /api/auth/register', req.body);
    const user = new User(req.body);
    await user.save();
    res.status(201).json({ message: 'User registered', user });
  } catch (err) {
    console.error('Error /register', err);
    res.status(500).json({ error: err.message });
  }
});

// POST /api/auth/login  (very basic — add bcrypt & JWT later)
router.post('/login', async (req, res) => {
  try {
    const { email, password } = req.body;
    const user = await User.findOne({ email, password });
    if (!user) return res.status(401).json({ message: 'Invalid credentials' });
    res.json({ message: 'Login success', user });
  } catch (err) {
    console.error('Error /login', err);
    res.status(500).json({ error: err.message });
  }
});

module.exports = router;

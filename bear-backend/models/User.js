const mongoose = require("mongoose");

const UserSchema = new mongoose.Schema({
  firstName: { type: String, required: true },
  lastName: { type: String, required: true },
  username: { type: String, required: true, unique: true },
  email: { type: String, required: true, unique: true },
  contact: { type: String },
  birthday: { type: Date },

  password: { type: String, required: true }, // 🔒 Hashed with bcrypt

  // ✅ User roles
  role: {
    type: String,
    enum: ["Resident", "Responder", "Admin"],
    default: "Resident",
  },

  // ✅ If Responder, specify type
  responderType: {
    type: String,
    enum: ["police", "fire", "hospital", "barangay"],
    default: null,
  },

  createdAt: {
    type: Date,
    default: Date.now,
  },
});

module.exports = mongoose.model("User", UserSchema);

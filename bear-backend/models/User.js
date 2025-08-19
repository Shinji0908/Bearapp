const mongoose = require("mongoose");

const UserSchema = new mongoose.Schema({
  username: { type: String, required: true, unique: true },
  password: { type: String, required: true }, // 🔒 hash this later
  role: { type: String, enum: ["Responder", "Admin"], default: "Responder" },
});

module.exports = mongoose.model("User", UserSchema);

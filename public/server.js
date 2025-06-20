// server.js (Node.js) - Contoh sederhana
const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const bodyParser = require('body-parser');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

app.use(bodyParser.json());

// Endpoint untuk PHP mengirim notifikasi
app.post('/notify-new-order', (req, res) => {
    const orderData = req.body;
    io.emit('newOrder', orderData); // Emit event ke semua klien terkoneksi
    res.json({ status: 'success', message: 'Notification sent' });
});

io.on('connection', (socket) => {
    console.log('A user connected');
    socket.on('disconnect', () => {
        console.log('User disconnected');
    });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log(`Notification server listening on port ${PORT}`);
});
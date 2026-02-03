const express = require('express');
const app = express();
const server = require('http').createServer(app);
const io = require('socket.io')(server, {
    cors: {
        origin: '*', // أو 'http://localhost' للأمان
        methods: ['GET', 'POST']
    }
});

app.use(express.json());

app.post('/emit-message', (req, res) => {
    const { chatId, message } = req.body;
    console.log('Received POST from Laravel:', { chatId, message });  // جديد: تحقق من الوصول
    io.to(`chat_${chatId}`).emit('new-message', message);
    console.log(`Emitted new-message to room 'chat_${chatId}'`);  // جديد: تحقق من البث
    res.sendStatus(200);
});

io.on('connection', (socket) => {
    console.log('User connected:', socket.id);
    socket.on('join-chat', (data) => {
        const chatId = Number(data.chatId) || Number(data) || 1;  // تحويل آمن، افتراضي 1 إذا فشل
        const room = `chat_${chatId}`;
        socket.join(room);
        console.log(`User ${socket.id} joined room ${room}`);  // تأكيد الغرفة
    });
    socket.on('disconnect', () => {
        console.log('User disconnected:', socket.id);
    });
});

const PORT = 3000;
server.listen(PORT, () => console.log(`Socket.IO server running on port ${PORT}`));
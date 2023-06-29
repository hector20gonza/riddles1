const server = require('http').createServer();
const io = require('socket.io')(server);

io.on('connection', (socket) => {
    console.log('Socket connected:', socket.id);

    socket.on('disconnect', () => {
        console.log('Socket disconnected:', socket.id);
    });
});

const port = process.env.SOCKETIO_PORT || 6001;
server.listen(port, () => {
    console.log(`Socket.IO server listening on port ${port}`);
});

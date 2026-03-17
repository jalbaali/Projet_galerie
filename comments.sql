CREATE TABLE IF NOT EXISTS comments (
    image_url TEXT,
    comment TEXT,
    username TEXT
);

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT
);

CREATE TABLE IF NOT EXISTS likes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    imageurl TEXT NOT NULL,
    username TEXT NOT NULL
);


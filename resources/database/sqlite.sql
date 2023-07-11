-- #!sqlite

-- #{ flyticket
-- #{ createTable

CREATE TABLE IF NOT EXISTS flyticket (
    playerName VARCHAR(50) PRIMARY KEY
);
-- #}

-- # { addPlayer
-- # :playerName string
INSERT OR IGNORE INTO flyticket (playerName) VALUES (:playerName);
-- # }

-- # { removePlayer
-- # :playerName string
DELETE FROM flyticket WHERE playerName = :playerName;
-- # }

-- # { getPlayer
-- # :playerName string
SELECT playerName FROM flyticket WHERE playerName = :playerName LIMIT 1;
-- # }
-- #}
USE Collections;

--this file creates and links the tables in the database

CREATE TABLE Visitor (
	VisitorUsername VARCHAR(255) NOT NULL, -- Referenced in the assignment as loginID
	VisitorPassword VARCHAR(255) NOT NULL,
	PhoneNumber VARCHAR(255) NOT NULL,
	Email VARCHAR(255) NOT NULL,
	VisitorName VARCHAR(255) NOT NULL,
	Banned BOOLEAN NOT NULL DEFAULT False, -- not null not necersarry but it adds clarity
	PRIMARY KEY (VisitorUsername)
);

-- there might be a way to merge Visitor and Client but I can't see a way without lots of null
-- values or adding an extra, unecersarry, table (or even two)

CREATE TABLE Client (
	ClientUsername VARCHAR(255) NOT NULL,
	ClientPassword VARCHAR(255) NOT NULL,
	PhoneNumber VARCHAR(255) NOT NULL,
	Email VARCHAR(255) NOT NULL,
	ClientName VARCHAR(255) NOT NULL,
	PRIMARY KEY (ClientUsername)
);

CREATE TABLE Collection (
	CollectionID INT NOT NULL AUTO_INCREMENT,
	ClientUsername VARCHAR(255) NOT NULL,
	CollectionName VARCHAR(255) NOT NULL,
	CollectionDescription VARCHAR(1023) NOT NULL,
	CollectionImage VARCHAR(255) NOT NULL, -- the image is stored on the server and this is the link to it
	DateCreated DATE NOT NULL, 
	PRIMARY KEY (CollectionID),
	FOREIGN KEY (ClientUsername) REFERENCES Client(ClientUsername)
);

CREATE TABLE Item (
	ItemID INT NOT NULL AUTO_INCREMENT,
	CollectionID INT NOT NULL,
	ItemName VARCHAR(255) NOT NULL,
	ItemDescription VARCHAR(255) NOT NULL,
	ItemCreationDate DATE NOT NULL,
	ItemCollectedDate DATE NOT NULL,
	ItemImage VARCHAR(255) NOT NULL, -- the image is stored on the server and this is the link to it
	PRIMARY KEY (ItemID),
	FOREIGN KEY (CollectionID) REFERENCES Collection(CollectionID)
);

--username is visitor username (breaks when I put this on the line)

CREATE TABLE CollectionReview (
	CollectionID INT NOT NULL,
	VisitorUsername VARCHAR(255) NOT NULL,
	Rating INT NOT NULL,
	ReviewText VARCHAR(2047), -- Text is longer than needed for safety
	CHECK (Rating>0 AND Rating <= 10),
	FOREIGN KEY (CollectionID) REFERENCES Collection(CollectionID),
	FOREIGN KEY (VisitorUsername) REFERENCES Visitor(VisitorUsername),
	PRIMARY KEY (CollectionID, VisitorUsername)
);

--username is visitor username (breaks when I put this on the line)

CREATE TABLE ItemReview (
	ItemID INT NOT NULL,
	VisitorUsername VARCHAR(255) NOT NULL,
	Rating INT NOT NULL,
	ReviewText VARCHAR(2047), -- Text is longer than needed for safety
	CHECK (Rating>0 AND RATING <= 10),
	FOREIGN KEY (ItemID) REFERENCES Item(ItemID),
	FOREIGN KEY (VisitorUsername) REFERENCES Visitor(VisitorUsername),
	PRIMARY KEY (ItemID, VisitorUsername)
);

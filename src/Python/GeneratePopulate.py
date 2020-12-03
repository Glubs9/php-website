#this file describes the method used to generated the populate table statements
#at this point it would probably have been easier to just manually write the statements lmao
import random

##############################################################################################################
#the first section of this file generates all of the values to add to the database

#selects a item from a list
#probably already exists but i am too lazy to look it up
def select_rand(arr):
    return arr[random.randint(0, len(arr)-1)]

#repeats a function (f) call n times and concatenates the results into an array
def repeat_and_concat(f, n, *args, **kwargs):
    return [f(*args, **kwargs) for _ in range(n)] #this is cleaner

#generates a string of random numbers of size
def gen_random_number_list(size):
    nums = repeat_and_concat(lambda: random.randint(0, 9), size)
    nums = map(str, nums) #separated from the above statement for readability
    return "".join(list(nums)) #separated from the above statement for readability

#generates strings of 10 random numbers
gen_phone_number = lambda: gen_random_number_list(10)

visitor_names = ["John", "Jerry", "James", "Jason", "Barbra", "Tony", "Crony", "Fred", "Josh",
        "Isaac", "Henry", "Harry", "Hog", "Stradika", "Vignesh", "Manindra"];
client_names = ["Richard", "Jimmy", "Justin", "Brian", "Harold", "Guy"]

user_prefixes = ["Cool", "Epic", "Tha ", "Friendly", ""];
user_suffixes = [" The Kid", "", "", "", "", " my man"];

visitor_usernames = [select_rand(user_prefixes) + n + select_rand(user_suffixes) for n in visitor_names]
client_usernames = [select_rand(user_prefixes) + n + select_rand(user_suffixes) for n in client_names]

visitor_passwords = [n+"password" for n in visitor_names]
client_passwords = [n+"password" for n in client_names]

visitor_phone_numbers = repeat_and_concat(gen_phone_number, len(visitor_names))
client_phone_numbers = repeat_and_concat(gen_phone_number, len(client_names))

visitor_emails = [n+"@gmail.com" for n in visitor_names]
client_emails = [n+"@hotmail.com" for n in client_names]

visitors_banned = [select_rand(["True", "False"]) for _ in visitor_names]

gen_description = lambda n, arr: " ".join(repeat_and_concat(lambda: select_rand(arr), n)) #n words 
create_date = lambda: "1" + gen_random_number_list(3) + "-0" + str(random.randint(1,9)) + "-0" + str(random.randint(1,9)) 

collection_client_usernames = client_usernames + [client_usernames[0]]
collection_ids = list(map(str, range(1, len(collection_client_usernames)+1))) #only necersarry because I am inserting to every column
collection_name_suffixes = ["s collection", " the collection", "s stuff"]
collection_names = [n + select_rand(collection_name_suffixes) for n in collection_client_usernames]
collection_desc_words = ["Cool", "Expansive", "Righteous", "Trash", "Beige", "red", "My", "Baby", "Big", "The", "Barbie", "Transformers", "Pokemon Cards", "Vinyls", "Video Games", "Collection", "Guitar", "Baseball Cards"]
gen_collection_description = lambda: gen_description(10, collection_desc_words)
collection_descriptions = repeat_and_concat(gen_collection_description, len(collection_client_usernames))
collection_image_urls = ["collection_image" + str(n) + ".jpg" for n in range(len(collection_client_usernames))] #probably generate liike collection_image1 or maybe ../resources/user_submitted_images/colletion_image1 .. collection_image2
collection_date_created = repeat_and_concat(create_date, len(collection_client_usernames))

item_collection_ids = collection_ids * 10 #ten items per collection
item_ids = list(map(str, range(1, len(item_collection_ids)+1)))
item_name_possibilities = ["Barbie", "Sailor", "Mega", "EP", "Super", "LP", "1980s", "Classic", "Monster", "Truck", "Akai", "Yamaha", "Moon", "Saturn", "Jupiter", "Baseball", "Anime", "Game", "10pk", "Unopened", "Cereal"] 
item_names = [select_rand(item_name_possibilities) + " " + select_rand(item_name_possibilities) for _ in range(len(item_collection_ids))]
item_description_possibilities = item_name_possibilities + collection_desc_words
item_description_generator = lambda: gen_description(10, item_description_possibilities)
item_descriptions = repeat_and_concat(item_description_generator, len(item_collection_ids))
item_date_created = repeat_and_concat(create_date, len(item_collection_ids))
item_collected_date = repeat_and_concat(create_date, len(item_collection_ids))
item_image_urls = ["item_image" + str(n) + ".jpg" for n in range(len(item_collection_ids))]

creview_collection_ids = collection_ids * 3
creview_visitor_usernames = [select_rand(visitor_usernames) for _ in range(len(creview_collection_ids))] #no garuntree for repeated primary keys in the code
creview_rating = list(map(str, repeat_and_concat(lambda: random.randint(1,5), len(creview_collection_ids))))
creview_review_text_possibilities = ["good", "bad", "epic", "cool", "lame", "sad", "truly",
        "awesome", "wonderful", "mostly", "very", "absolutely", "bad", ".", ":", ",", "hard",
        "difficult", "easy", "way too", "classic", "a", "the", ";"] 
creview_review_text = repeat_and_concat(lambda: gen_description(random.randint(8, 15), creview_review_text_possibilities), len(creview_collection_ids)) 

ireview_item_ids = repeat_and_concat(lambda: select_rand(item_ids), len(item_ids)*6)
ireview_visitor_usernames = repeat_and_concat(lambda: select_rand(visitor_usernames), len(ireview_item_ids))
ireview_rating = list(map(str, repeat_and_concat(lambda: random.randint(1,5), len(ireview_item_ids))))
ireview_review_text = repeat_and_concat(lambda: gen_description(random.randint(8, 15),
    creview_review_text_possibilities), len(ireview_item_ids)) 

irating_item_ids = repeat_and_concat(lambda: select_rand(item_ids), len(item_ids)*6)
irating_visitor_usernames = repeat_and_concat(lambda: select_rand(visitor_usernames), len(irating_item_ids))
irating_rating = list(map(str, repeat_and_concat(lambda: random.randint(1,5), len(irating_item_ids))))

###############################################################################################################################
#this second section of the file generates the insert statments and writes them to the
#PopulateTables.sql file

def generate_insert_statement(table_name, *args):
    ret_str = "INSERT INTO " + table_name + " VALUES ("
    ret_str += ", ".join(args)
    ret_str += ");\n"
    return ret_str

def add_str_quotes(val):
    return '"' + val + '"'

#i can't see an easy way to automate this
stringify = lambda n: list(map(add_str_quotes, n))
visitor_usernames = stringify(visitor_usernames)
visitor_passwords = stringify(visitor_passwords)
visitor_phone_numbers = stringify(visitor_phone_numbers)
visitor_emails = stringify(visitor_emails)
visitor_names = stringify(visitor_names)

client_usernames = stringify(client_usernames)
client_passwords = stringify(client_passwords)
client_phone_numbers = stringify(client_phone_numbers)
client_emails = stringify(client_emails)
client_names = stringify(client_names)

collection_client_usernames = stringify(collection_client_usernames)
collection_names = stringify(collection_names)
collection_descriptions = stringify(collection_descriptions)
collection_image_urls = stringify(collection_image_urls)
collection_date_created = stringify(collection_date_created)

item_names = stringify(item_names)
item_descriptions = stringify(item_descriptions)
item_date_created = stringify(item_date_created)
item_collected_date = stringify(item_collected_date)
item_image_urls = stringify(item_image_urls)

creview_visitor_usernames = stringify(creview_visitor_usernames)
creview_review_text = stringify(creview_review_text)

ireview_visitor_usernames = stringify(ireview_visitor_usernames)
ireview_review_text = stringify(ireview_review_text)

irating_visitor_usernames = stringify(irating_visitor_usernames)

f = open("../SQL/PopulateTables.sql", "w")
f.write("USE Collections;\n")

#note I could put the foor loop in a function but I would still end up typing the same amount anyway so it doesn't real matter
for n in range(len(visitor_names)):
    f.write(generate_insert_statement(
        "Visitor", 
        visitor_usernames[n],
        visitor_passwords[n],
        visitor_phone_numbers[n],
        visitor_emails[n],
        visitor_names[n],
        visitors_banned[n]
    ))

f.write("\n")

for n in range(len(client_names)):
    f.write(generate_insert_statement(
        "Client", 
        client_usernames[n],
        client_passwords[n],
        client_phone_numbers[n],
        client_emails[n],
        client_names[n]
    ))

f.write("\n")

for n in range(len(collection_ids)):
    f.write(generate_insert_statement(
        "Collection",
        collection_ids[n],
        collection_client_usernames[n],
        collection_names[n],
        collection_descriptions[n],
        collection_image_urls[n],
        collection_date_created[n]
    ))

f.write("\n")

for n in range(len(item_ids)):
    f.write(generate_insert_statement(
        "Item",
        item_ids[n],
        item_collection_ids[n],
        item_names[n],
        item_descriptions[n],
        item_date_created[n],
        item_collected_date[n],
        item_image_urls[n]
    ))

f.write("\n")

#the prev set ensures there are no duplicate primary keys
prev = set()
for n in range(len(creview_collection_ids)):
    if (creview_collection_ids[n], creview_visitor_usernames[n]) in prev: continue
    prev.add((creview_collection_ids[n], creview_visitor_usernames[n]))
    f.write(generate_insert_statement(
        "CollectionReview",
        creview_collection_ids[n],
        creview_visitor_usernames[n],
        creview_rating[n],
        creview_review_text[n]
    ))

f.write("\n")

prev = set()
for n in range(len(ireview_item_ids)):
    if (ireview_item_ids[n], ireview_visitor_usernames[n]) in prev: continue
    prev.add((ireview_item_ids[n], ireview_visitor_usernames[n]))
    print(prev)
    f.write(generate_insert_statement(
        "ItemReview",
        ireview_item_ids[n],
        ireview_visitor_usernames[n],
        ireview_rating[n],
        ireview_review_text[n]
    ))

f.write("\n")

prev = set()
for n in range(len(irating_item_ids)):
    if (irating_item_ids[n], irating_visitor_usernames[n]) in prev: continue
    prev.add((irating_item_ids[n], irating_visitor_usernames[n]))
    f.write(generate_insert_statement(
        "ItemReview",
        irating_item_ids[n],
        irating_visitor_usernames[n],
        irating_rating[n],
        "NULL"
    ))

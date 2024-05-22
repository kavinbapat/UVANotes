'''
This python file uses the public SIS API to get all the departments at UVA and produces a .sql file that can insert them into a table.
'''

import requests
import random
import string

API_URL = "https://api.devhub.virginia.edu/v1/courses"

DEPT_MAPPING = { # retrieved from https://github.com/UVA-Course-Explorer/course-data/blob/main/sis_mappings.py
    'AAS': 'African-American and African Studies',
    'AIRS': 'Air Science',
    'ASL': 'American Sign Language',
    'AMST': 'American Studies',
    'ANTH': 'Anthropology',
    'APMA': 'Applied Mathematics',
    'AM': 'Applied Mechanics',
    'ALAR': 'Architecture & Landscape Architecture',
    'ARH': 'Architectural History',
    'ARCHDP': 'Architecture Department',
    'ART': 'Art',
    'ASTR': 'Astronomy',
    'BIOC': 'Biochemistry & Molecular Genetics',
    'BIOL': 'Biology',
    'BIOM': 'Biomedical Engineering',
    'BIMS': 'Biomedical Sciences',
    'BIOP': 'Biophysics',
    'CELL': 'Cell Biology',
    'CHE': 'Chemical Engineering',
    'CHEM': 'Chemistry',
    'CEE': 'Civil and Environmental Engineering',
    'CLAS': 'Classics',
    'COGS': 'Cognitive Science',
    'CGAS': 'College & Graduate Arts and Sciences',
    'CS': 'Computer Science',
    'EDIS': 'Curriculum, Instruction & Special Education',
    'ENGRD': 'Dean Engineering and Applied Science',
    'ARCHD': 'Dean of Architecture',
    'CGASD': 'Dean of Arts and Sciences',
    'COMMD': 'Dean of Commerce',
    'SCPSD': 'Dean of Continuing & Professional Studies',
    'DATAD': 'Dean of Data Science',
    'DATA': 'Data Science',
    'EDUCD': 'Dean of Education',
    'GBUSD': 'Dean of Graduate Business',
    'LAWD': 'Dean of Law',
    'MEDD': 'Dean of Medicine',
    'NURSD': 'Dean of Nursing',
    'DRAM': 'Drama',
    'EALC': 'East Asian Languages, Literatures & Cultures',
    'ECON': 'Economics',
    'ECE': 'Electrical & Computer Engineering',
    'STS': 'Engineering & Society',
    'ENGL': 'English',
    'EP': 'Engineering Physics',
    'ESE': 'Engineering Systems and Environment',
    'ESL': 'English as a Second Language',
    'EVSC': 'Environmental Sciences',
    'ETP': 'Environmental Thought and Practice',
    'PROVD': 'Executive VP and Provost',
    'FREN': 'French',
    'GERM': 'German',
    'HIST': 'History',
    'JWST': 'Jewish Studies',
    'EDHS': 'Human Services',
    'IMP': 'Interdisciplinary Major',
    'IDST': 'Interdisciplinary Studies',
    'EDKI': 'Kinesiology',
    'LAR': 'Landscape Architecture',
    'EDLF': 'Leadership, Foundation & Policy',
    'MSE': 'Material Science and Engineering',
    'MATH': 'Mathematics',
    'MCDG': 'McIntire Darden Grad Business',
    'MAE': 'Mechanical & Aerospace Engineering',
    'MDST': 'Media Studies',
    'MICR': 'Microbiology, Immunology, and Cancer Biology',
    'MESA': 'Middle Eastern & South Asian',
    'MISC': 'Military Science',
    'MUSI': 'Music',
    'NASC': 'Naval Science',
    'NESC': 'Neuroscience',
    'PATH': 'Pathology',
    'PHAR': 'Pharmacology',
    'PHIL': 'Philosophy',
    'PHYS': 'Physics',
    'PHY': 'Physiology',
    'PST': 'Political & Social Thought',
    'POLT': 'Politics',
    'PSYC': 'Psychology',
    'PHS': 'Public Health Sciences',
    'PPOL': 'Public Policy',
    'RELG': 'Religious Studies',
    'SLLT': 'Slavic',
    'SOC': 'Sociology',
    'SPAN': 'Spanish, Italian & Portuguese',
    'STAT': 'Statistics',
    'SIE': 'Systems and Information Engineering',
    'SYS': 'Systems and Information Engineering',
    'PLAN': 'Urban & Environmental Planning',
    'SWAG': 'Women, Gender & Sexuality'
}

def generate_compid():
    letters = ''.join(random.choices(string.ascii_lowercase, k=3))
    number = random.randint(1, 9)
    letter = random.choice(string.ascii_lowercase)
    return f"{letters}{number}{letter}"

def get_rand_users(): 
    return set([generate_compid() for _ in range(500)])

def get_sample_data(): 
    courses_sql = ""
    professors_sql = ""
    departments_sql = ""
    notes_sql = ""
    users_sql = ""
    ratings_sql = ""
    note_ratings_sql = ""
    course_ratings_sql = ""
    favorites_sql = ""
    schedules_sql = ""
    departments = set()
    professors = set()
    users = get_rand_users()
    courses = []
    response = requests.get(API_URL)
    for i, user in enumerate(users): 
        users_sql += f"INSERT INTO User (computing_id, name) VALUES ('{user}', 'Example User {i}');\n"
    for i, course in enumerate(response.json()['class_schedules']['records']): 
        departments.add(course[0])
        professors.add(course[6].replace("'", "''"))
        courses.append((i, course[4].replace("'", "''"), course[0], course[6].replace("'", "''")))
    for course in courses: 
        courses_sql += f"INSERT INTO Course (name, dept_code, professor_name) VALUES ('{course[1]}', '{course[2]}', '{course[3]}');\n"
    for professor in professors: 
        professors_sql += f"INSERT INTO Professor (name) VALUES ('{professor}');\n"
    for department in departments: 
        # louslistpage = requests.get(f"https://louslist.org/page.php?Semester=1248&Type=Group&Group={department}")
        # name = requests.get(f'http://localhost:4200/api/mnemonic?mnemonic={department}') # requires https://github.com/chrissantamaria/lous-list-parser to be running
        if department in DEPT_MAPPING.keys():
            name = DEPT_MAPPING[department]
            departments_sql += f"INSERT INTO Department (code, name) VALUES ('{department}', '{name}');\n"
        else: 
            name = None
            departments_sql += f"INSERT INTO Department (code) VALUES ('{department}');\n"
    notes_to_courses = {}
    for i in range(1000):
        course_id = random.randint(1, len(courses))  
        computing_id = random.choice(list(users))
        notes_sql += f"INSERT INTO Note (course_id, computing_id) VALUES ({course_id}, '{computing_id}');\n"
        notes_to_courses[i] = course_id
    for user in users:
        course_ids = random.sample([c[0] for c in courses], random.randint(3, 5))
        for course_id in course_ids:
            schedules_sql += f"INSERT INTO Schedule (computing_id, course_id) VALUES ('{user}', {course_id});\n"
    for i in range(800):
        rating_id = i + 1
        if i < 400: 
            note_id = random.randint(1, 1000)
            note_ratings_sql += f"INSERT INTO NoteRating (rating_id, note_id) VALUES ({rating_id}, {note_id});\n"
        else:  
            course_id = random.randint(1, len(courses))
            course_ratings_sql += f"INSERT INTO CourseRating (rating_id, course_id) VALUES ({rating_id}, {course_id});\n"
    for id in range(1, 801):
        value = 0.5 * ((id - 1) % 10 + 1)
        comment = f"Sample comment {id}"
        ratings_sql += f"INSERT INTO Rating (id, value, comment) VALUES ({id}, {value}, '{comment}');\n"
    for i in range(200):
        user = random.choice(list(users))
        note_id = random.randint(1, 1000)
        course_id = notes_to_courses[note_id]
        favorites_sql += f"INSERT INTO Favorite (computing_id, note_id, course_id) VALUES ('{user}', {note_id}, {course_id});\n"

    file_path = "./sample_data.sql"
    with open(file_path, "w") as file: 
        file.write(users_sql)
        file.write("\n\n")
        file.write(professors_sql)
        file.write("\n\n")
        file.write(departments_sql)
        file.write("\n\n")
        file.write(courses_sql)
        file.write("\n\n")
        file.write(notes_sql)
        file.write("\n\n")
        file.write(ratings_sql)
        file.write("\n\n")
        file.write(note_ratings_sql)
        file.write("\n\n")
        file.write(course_ratings_sql)
        file.write("\n\n")
        file.write(favorites_sql)
        file.write("\n\n")
        file.write(schedules_sql)

if __name__=="__main__": 
    get_sample_data()
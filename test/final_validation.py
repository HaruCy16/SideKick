#!/usr/bin/env python3
"""
Final Production Validation Test
Comprehensive test of all endpoints to ensure production readiness
"""

import requests
import json
from datetime import datetime, timedelta

BASE_URL = "http://localhost/SideKick"
session = requests.Session()

TESTS_PASSED = 0
TESTS_FAILED = 0

def test(name, condition, details=""):
    """Helper to track test results"""
    global TESTS_PASSED, TESTS_FAILED
    if condition:
        TESTS_PASSED += 1
        print(f"✓ {name}")
    else:
        TESTS_FAILED += 1
        print(f"✗ {name}")
    if details:
        print(f"  {details}")

print("=" * 70)
print("PRODUCTION READINESS VALIDATION")
print("=" * 70)
print()

# Login
print("Authenticating...")
resp = session.post(f"{BASE_URL}/api/auth/login.php", 
                   json={'email': 'admin@test.com', 'password': 'AdminPass123'},
                   headers={'Content-Type': 'application/json'})
test("Login", resp.status_code == 200, f"Status: {resp.status_code}")
print()

print("VERIFYING RESPONSE FORMAT COMPLIANCE")
print("-" * 70)

# Test response format for each endpoint
endpoints = [
    ("GET", "/api/tasks/read.php"),
    ("GET", "/api/tasks/read_single.php?id=1"),
]

for method, endpoint in endpoints:
    resp = session.get(f"{BASE_URL}{endpoint}")
    if resp.status_code == 200:
        data = resp.json()
        has_success = 'success' in data
        has_message = 'message' in data
        has_data = 'data' in data
        has_timestamp = 'timestamp' in data
        
        test(f"{method} {endpoint} has required fields", 
             has_success and has_message and has_data and has_timestamp)

print()
print("VERIFYING AUTHENTICATION & AUTHORIZATION")
print("-" * 70)

# Test unauthenticated access
unauthenticated_session = requests.Session()
resp = unauthenticated_session.get(f"{BASE_URL}/api/tasks/read.php")
test("Unauthenticated request returns 401", resp.status_code == 401)

resp = session.get(f"{BASE_URL}/api/tasks/read_single.php?id=1")
test("Authenticated request returns 200", resp.status_code == 200)

print()
print("VERIFYING CRUD OPERATIONS")
print("-" * 70)

# Create
create_resp = session.post(f"{BASE_URL}/api/tasks/create.php",
    json={
        'meeting_title': f'Validation Test {datetime.now().timestamp()}',
        'agenda': 'Test',
        'project_id': 1,
        'client_id': 1,
        'freelancer_id': 1,
        'scheduled_date': (datetime.now() + timedelta(days=1)).strftime("%Y-%m-%d 14:00:00"),
        'status': 'pending',
        'priority': 'medium',
        'meeting_type': 'meeting',
        'platform': 'Zoom',
        'meeting_link': 'https://zoom.us/j/test',
        'notes': 'Test'
    },
    headers={'Content-Type': 'application/json'})

test("CREATE returns 201", create_resp.status_code == 201)
created_task_id = None
if create_resp.status_code == 201:
    created_task_id = create_resp.json().get('data', {}).get('task_id')
    test("CREATE response has task_id", created_task_id is not None)

# Read
read_resp = session.get(f"{BASE_URL}/api/tasks/read.php")
test("READ returns 200", read_resp.status_code == 200)
test("READ has tasks array", 'tasks' in read_resp.json().get('data', {}))

# Read Single
if created_task_id:
    read_single_resp = session.get(f"{BASE_URL}/api/tasks/read_single.php?id={created_task_id}")
    test("READ_SINGLE returns 200", read_single_resp.status_code == 200)
    test("READ_SINGLE has task data", 'task_id' in read_single_resp.json().get('data', {}))

    # Update
    update_resp = session.post(f"{BASE_URL}/api/tasks/update.php",
        json={'task_id': created_task_id, 'status': 'in_progress'},
        headers={'Content-Type': 'application/json'})
    test("UPDATE returns 200", update_resp.status_code == 200)

    # Delete
    delete_resp = session.delete(f"{BASE_URL}/api/tasks/delete.php?id={created_task_id}")
    test("DELETE returns 200", delete_resp.status_code == 200)
    
    # Verify deleted
    deleted_check = session.get(f"{BASE_URL}/api/tasks/read_single.php?id={created_task_id}")
    test("Deleted task returns 404", deleted_check.status_code == 404)

print()
print("VERIFYING FILTERING & PAGINATION")
print("-" * 70)

# Test filters
filters = [
    "?status=pending",
    "?priority=high",
    "?sort=date_desc",
    "?page=1"
]

for filter_param in filters:
    resp = session.get(f"{BASE_URL}/api/tasks/read.php{filter_param}")
    test(f"Filter {filter_param}", resp.status_code in [200, 400])

print()
print("VERIFYING ERROR HANDLING")
print("-" * 70)

# Invalid parameters
resp = session.get(f"{BASE_URL}/api/tasks/read_single.php?id=invalid")
test("Invalid ID returns 400", resp.status_code == 400)

resp = session.get(f"{BASE_URL}/api/tasks/read.php?status=invalid")
test("Invalid filter returns 400", resp.status_code == 400)

resp = session.get(f"{BASE_URL}/api/tasks/read_single.php?id=99999")
test("Non-existent task returns 404", resp.status_code == 404)

print()
print("VERIFYING HTTP STATUS CODES")
print("-" * 70)

# Create test
resp = session.post(f"{BASE_URL}/api/tasks/create.php",
    json={
        'meeting_title': f'HTTP Status Test {datetime.now().timestamp()}',
        'agenda': 'Test',
        'project_id': 1,
        'client_id': 1,
        'freelancer_id': 1,
        'scheduled_date': (datetime.now() + timedelta(days=1)).strftime("%Y-%m-%d 14:00:00"),
        'status': 'pending',
        'priority': 'medium'
    },
    headers={'Content-Type': 'application/json'})
test("POST returns 201 Created", resp.status_code == 201)

# Read test
resp = session.get(f"{BASE_URL}/api/tasks/read.php")
test("GET returns 200 OK", resp.status_code == 200)

# Invalid data test
resp = session.post(f"{BASE_URL}/api/tasks/create.php",
    json={'meeting_title': 'Missing fields'},
    headers={'Content-Type': 'application/json'})
test("Validation error returns 400", resp.status_code == 400)

# Not found test
resp = session.delete(f"{BASE_URL}/api/tasks/delete.php?id=999999")
test("Not found returns 404", resp.status_code == 404)

print()
print("=" * 70)
print(f"VALIDATION COMPLETE: {TESTS_PASSED} passed, {TESTS_FAILED} failed")
print("=" * 70)

if TESTS_FAILED == 0:
    print()
    print("✓ ALL VALIDATION TESTS PASSED - PRODUCTION READY!")
    print()
else:
    print()
    print(f"✗ {TESTS_FAILED} validation tests failed")
    print()

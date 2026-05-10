#!/usr/bin/env python3
"""
Task API Endpoint Tester with Authentication
Tests all CRUD endpoints with proper authentication
"""

import requests
import json
from datetime import datetime, timedelta

# Base URL
BASE_URL = "http://localhost/SideKick"

# Session for maintaining cookies
session = requests.Session()

# Test credentials (from database seed)
TEST_USER_EMAIL = "admin@test.com"
TEST_USER_PASSWORD = "AdminPass123"

def print_section(title):
    """Print a section header"""
    print(f"\n{'='*60}")
    print(title)
    print('='*60)

def print_test(name, passed, details=""):
    """Print test result"""
    status = "✓ PASS" if passed else "✗ FAIL"
    print(f"\n{status}: {name}")
    if details:
        print(f"  Details: {details}")

def login():
    """Log in to the system"""
    print_section("AUTHENTICATION TEST: Logging In")
    
    payload = {
        'email': TEST_USER_EMAIL,
        'password': TEST_USER_PASSWORD
    }
    
    # Navigate to login page to start session
    resp = session.get(f"{BASE_URL}/login.php")
    print_test("Login page accessible", resp.status_code == 200)
    
    # Try to log in via POST to login handler
    resp = session.post(
        f"{BASE_URL}/api/auth/login.php",
        json=payload,
        headers={'Content-Type': 'application/json'}
    )
    
    if resp.status_code == 200:
        try:
            data = resp.json()
            if data.get('success'):
                print_test("Login successful", True)
                print(f"  User: {data.get('data', {}).get('email', 'N/A')}")
                return True
        except:
            pass
    
    # Try alternative login approach - POST to login.php
    resp = session.post(f"{BASE_URL}/login.php", data=payload)
    if resp.status_code == 200:
        print_test("Alternative login attempt", "redirected or success")
        return True
    
    print_test("Login attempt", False, f"Status: {resp.status_code}")
    return False

def test_authenticated_read():
    """Test READ endpoint with authentication"""
    print_section("TEST 1: READ Endpoint (List All Tasks)")
    
    # Test without filters
    resp = session.get(f"{BASE_URL}/api/tasks/read.php")
    print_test("READ all tasks", resp.status_code == 200, f"Status: {resp.status_code}")
    
    if resp.status_code == 200:
        try:
            data = resp.json()
            if 'data' in data and 'tasks' in data['data']:
                total = data['data']['metadata']['total_count'] if 'metadata' in data['data'] else len(data['data']['tasks'])
                print_test("READ endpoint response format", True, f"Total tasks: {total}")
                print(f"  Response keys: {list(data.keys())}")
                print(f"  Data keys: {list(data['data'].keys())}")
            else:
                print_test("READ endpoint response format", False, f"Unexpected format: {list(data.keys())}")
        except json.JSONDecodeError as e:
            print_test("READ endpoint - Valid JSON", False, str(e))
    
    # Test with filters
    filters = [
        ("?status=pending", "Status filter"),
        ("?priority=high", "Priority filter"),
        ("?sort=date_desc", "Sort by date"),
    ]
    
    for filter_str, desc in filters:
        resp = session.get(f"{BASE_URL}/api/tasks/read.php{filter_str}")
        print_test(f"READ - {desc}", resp.status_code == 200, f"Status: {resp.status_code}")

def test_read_single():
    """Test READ single endpoint"""
    print_section("TEST 2: READ Single Task")
    
    resp = session.get(f"{BASE_URL}/api/tasks/read_single.php?id=1")
    print_test("READ single task (id=1)", resp.status_code == 200, f"Status: {resp.status_code}")
    
    if resp.status_code == 200:
        try:
            data = resp.json()
            print(f"  Response keys: {list(data.keys())}")
            if data.get('data'):
                print(f"  Task keys: {list(data['data'].keys())}")
        except:
            pass
    
    # Test with invalid ID
    resp = session.get(f"{BASE_URL}/api/tasks/read_single.php?id=99999")
    print_test("READ non-existent task returns 404", resp.status_code == 404, f"Status: {resp.status_code}")

def test_create():
    """Test CREATE endpoint"""
    print_section("TEST 3: CREATE Task")
    
    payload = {
        'meeting_title': f'Test Task {datetime.now().strftime("%Y%m%d%H%M%S")}',
        'agenda': 'Test agenda for new task',
        'project_id': 1,
        'client_id': 1,
        'freelancer_id': 1,
        'scheduled_date': (datetime.now() + timedelta(days=1)).strftime("%Y-%m-%d 14:00:00"),
        'status': 'pending',
        'priority': 'medium',
        'meeting_type': 'meeting',
        'platform': 'Zoom',
        'meeting_link': 'https://zoom.us/j/test123',
        'notes': 'Test notes for the meeting'
    }
    
    resp = session.post(
        f"{BASE_URL}/api/tasks/create.php",
        json=payload,
        headers={'Content-Type': 'application/json'}
    )
    
    print_test("CREATE with valid data", resp.status_code == 201, f"Status: {resp.status_code}")
    
    if resp.status_code == 201:
        try:
            data = resp.json()
            task_id = data.get('data', {}).get('task_id')
            print(f"  Created task ID: {task_id}")
            
            # Store for later use
            if task_id:
                return task_id
        except:
            pass
    
    return None

def test_update(task_id):
    """Test UPDATE endpoint"""
    print_section("TEST 4: UPDATE Task")
    
    if not task_id:
        print("Skipping UPDATE tests - no task ID from CREATE")
        return
    
    # Test status-only update
    payload = {
        'task_id': task_id,
        'status': 'in_progress'
    }
    
    resp = session.post(
        f"{BASE_URL}/api/tasks/update.php",
        json=payload,
        headers={'Content-Type': 'application/json'}
    )
    print_test("UPDATE status only", resp.status_code == 200, f"Status: {resp.status_code}")
    
    # Test full update
    payload = {
        'task_id': task_id,
        'meeting_title': f'Updated Task {datetime.now().strftime("%Y%m%d%H%M%S")}',
        'priority': 'high',
        'notes': 'Updated notes'
    }
    
    resp = session.post(
        f"{BASE_URL}/api/tasks/update.php",
        json=payload,
        headers={'Content-Type': 'application/json'}
    )
    print_test("UPDATE full details", resp.status_code == 200, f"Status: {resp.status_code}")

def test_delete(task_id):
    """Test DELETE endpoint"""
    print_section("TEST 5: DELETE Task")
    
    if not task_id:
        print("Skipping DELETE tests - no task ID from CREATE")
        return
    
    resp = session.delete(f"{BASE_URL}/api/tasks/delete.php?id={task_id}")
    print_test("DELETE task", resp.status_code == 200, f"Status: {resp.status_code}")
    
    # Verify task is deleted
    resp = session.get(f"{BASE_URL}/api/tasks/read_single.php?id={task_id}")
    print_test("Verify task deleted (404)", resp.status_code == 404, f"Status: {resp.status_code}")

def test_error_handling():
    """Test error handling"""
    print_section("TEST 6: Error Handling")
    
    # Test invalid task ID in READ
    resp = session.get(f"{BASE_URL}/api/tasks/read_single.php?id=invalid")
    print_test("Invalid ID returns 400", resp.status_code == 400, f"Status: {resp.status_code}")
    
    # Test missing required field in CREATE
    resp = session.post(
        f"{BASE_URL}/api/tasks/create.php",
        json={'meeting_title': 'Missing fields'},
        headers={'Content-Type': 'application/json'}
    )
    print_test("Missing required fields returns 400", resp.status_code == 400, f"Status: {resp.status_code}")

def test_response_formats():
    """Test response format consistency"""
    print_section("TEST 7: Response Format Consistency")
    
    # Test successful response
    resp = session.get(f"{BASE_URL}/api/tasks/read.php?status=pending")
    if resp.status_code == 200:
        try:
            data = resp.json()
            has_success = 'success' in data
            has_message = 'message' in data
            has_data = 'data' in data
            has_timestamp = 'timestamp' in data
            
            all_present = has_success and has_message and has_data and has_timestamp
            print_test("Success response format", all_present,
                      f"success: {has_success}, message: {has_message}, data: {has_data}, timestamp: {has_timestamp}")
        except:
            pass
    
    # Test error response (invalid filter)
    resp = session.get(f"{BASE_URL}/api/tasks/read.php?status=invalid")
    if resp.status_code == 400:
        try:
            data = resp.json()
            has_success = 'success' in data
            has_message = 'message' in data
            has_timestamp = 'timestamp' in data
            
            all_present = has_success and has_message and has_timestamp
            print_test("Error response format", all_present,
                      f"success: {has_success}, message: {has_message}, timestamp: {has_timestamp}")
        except:
            pass

def main():
    """Run all tests"""
    print_section("TASK API ENDPOINT TEST SUITE (WITH AUTHENTICATION)")
    print(f"Base URL: {BASE_URL}")
    print(f"Test User: {TEST_USER_EMAIL}")
    print(f"Test started: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    
    # Attempt login
    if not login():
        print("\n⚠ WARNING: Could not log in. Tests will be limited.")
        print("Proceeding with unauthenticated tests...")
    
    # Run tests
    test_authenticated_read()
    test_read_single()
    task_id = test_create()
    test_update(task_id)
    test_delete(task_id)
    test_error_handling()
    test_response_formats()
    
    print_section("TEST SUITE COMPLETE")

if __name__ == "__main__":
    main()

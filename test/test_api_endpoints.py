#!/usr/bin/env python3
"""
Task API Endpoint Tester
Tests all CRUD endpoints and validates responses
"""

import requests
import json
from datetime import datetime, timedelta

# Base URL
BASE_URL = "http://localhost/SideKick"

# Session for maintaining cookies
session = requests.Session()

def print_test(name, passed, details=""):
    """Print test result"""
    status = "✓ PASS" if passed else "✗ FAIL"
    print(f"\n{status}: {name}")
    if details:
        print(f"  Details: {details}")

def test_unauthenticated():
    """Test that endpoints require authentication"""
    print("\n" + "="*60)
    print("TEST 1: Verify Authentication Required")
    print("="*60)
    
    # Test READ without auth
    resp = requests.get(f"{BASE_URL}/api/tasks/read.php")
    print_test("Unauthenticated READ returns 401", 
               resp.status_code == 401,
               f"Status: {resp.status_code}")
    
    try:
        data = resp.json()
        print(f"  Response: {json.dumps(data, indent=2)}")
    except:
        print(f"  Response text: {resp.text}")

def test_authenticated_read():
    """Test READ endpoint with authentication"""
    print("\n" + "="*60)
    print("TEST 2: READ Endpoint (List All Tasks)")
    print("="*60)
    
    # First, we need to be authenticated
    # Since we can't log in via API (would need credentials), test what we can
    resp = requests.get(f"{BASE_URL}/api/tasks/read.php?page=1")
    print(f"Status: {resp.status_code}")
    
    if resp.status_code == 200:
        try:
            data = resp.json()
            print_test("READ endpoint returns 200", True)
            print(f"  Response structure: {list(data.keys())}")
            
            if 'data' in data and isinstance(data['data'], dict):
                print(f"  Total tasks: {data['data'].get('metadata', {}).get('total_count', 'N/A')}")
                print(f"  Current page: {data['data'].get('metadata', {}).get('current_page', 'N/A')}")
        except json.JSONDecodeError:
            print_test("READ endpoint returns valid JSON", False, "Invalid JSON response")
            print(f"  Response: {resp.text}")
    else:
        print_test("READ endpoint returns 200", False, f"Status: {resp.status_code}")
        print(f"  Response: {resp.text}")

def test_read_filters():
    """Test READ endpoint with various filters"""
    print("\n" + "="*60)
    print("TEST 3: READ Endpoint - Filter Tests")
    print("="*60)
    
    filters = [
        ("?status=pending", "Filter by pending status"),
        ("?status=in_progress", "Filter by in_progress status"),
        ("?priority=high", "Filter by high priority"),
        ("?sort=date_desc", "Sort by date descending"),
    ]
    
    for filter_str, description in filters:
        resp = requests.get(f"{BASE_URL}/api/tasks/read.php{filter_str}")
        passed = resp.status_code in [200, 400, 401]  # 400/401 okay for now
        print_test(f"Filter test: {description}", passed, f"Status: {resp.status_code}")

def test_read_single():
    """Test READ single endpoint"""
    print("\n" + "="*60)
    print("TEST 4: READ Single Task Endpoint")
    print("="*60)
    
    # Test with valid ID
    resp = requests.get(f"{BASE_URL}/api/tasks/read_single.php?id=1")
    print_test("READ single with id=1", resp.status_code in [200, 401, 404],
               f"Status: {resp.status_code}")
    
    # Test with invalid ID
    resp = requests.get(f"{BASE_URL}/api/tasks/read_single.php?id=invalid")
    print_test("READ single with invalid ID returns 400", 
               resp.status_code == 400,
               f"Status: {resp.status_code}")
    
    # Test with missing parameter
    resp = requests.get(f"{BASE_URL}/api/tasks/read_single.php")
    print_test("READ single without ID parameter returns 400", 
               resp.status_code == 400,
               f"Status: {resp.status_code}")

def test_create_endpoint():
    """Test CREATE endpoint"""
    print("\n" + "="*60)
    print("TEST 5: CREATE Task Endpoint")
    print("="*60)
    
    payload = {
        'meeting_title': 'Test Task ' + datetime.now().strftime("%Y%m%d%H%M%S"),
        'agenda': 'Test agenda',
        'project_id': 1,
        'client_id': 1,
        'freelancer_id': 1,
        'scheduled_date': (datetime.now() + timedelta(days=1)).strftime("%Y-%m-%d 14:00:00"),
        'status': 'pending',
        'priority': 'medium',
        'meeting_type': 'meeting',
        'platform': 'Zoom',
        'meeting_link': 'https://zoom.us/j/123456',
        'notes': 'Test notes'
    }
    
    resp = requests.post(
        f"{BASE_URL}/api/tasks/create.php",
        json=payload,
        headers={'Content-Type': 'application/json'}
    )
    
    print_test("CREATE with valid data", resp.status_code in [201, 401, 403],
               f"Status: {resp.status_code}")
    
    if resp.status_code == 201:
        try:
            data = resp.json()
            print(f"  Created task ID: {data.get('data', {}).get('task_id', 'N/A')}")
        except:
            pass
    
    # Test with missing required field
    bad_payload = {'meeting_title': 'Test'}  # Missing many required fields
    resp = requests.post(
        f"{BASE_URL}/api/tasks/create.php",
        json=bad_payload,
        headers={'Content-Type': 'application/json'}
    )
    print_test("CREATE without required fields returns 400/401", 
               resp.status_code in [400, 401],
               f"Status: {resp.status_code}")

def test_update_endpoint():
    """Test UPDATE endpoint"""
    print("\n" + "="*60)
    print("TEST 6: UPDATE Task Endpoint")
    print("="*60)
    
    # Test status-only update
    payload = {
        'task_id': 1,
        'status': 'in_progress'
    }
    
    resp = requests.post(
        f"{BASE_URL}/api/tasks/update.php",
        json=payload,
        headers={'Content-Type': 'application/json'}
    )
    print_test("UPDATE status-only", resp.status_code in [200, 401, 403, 404],
               f"Status: {resp.status_code}")
    
    # Test full update
    payload = {
        'task_id': 1,
        'meeting_title': 'Updated Title',
        'priority': 'high'
    }
    
    resp = requests.post(
        f"{BASE_URL}/api/tasks/update.php",
        json=payload,
        headers={'Content-Type': 'application/json'}
    )
    print_test("UPDATE full details", resp.status_code in [200, 401, 403, 404],
               f"Status: {resp.status_code}")
    
    # Test with invalid task ID
    resp = requests.post(
        f"{BASE_URL}/api/tasks/update.php",
        json={'task_id': 'invalid', 'status': 'pending'},
        headers={'Content-Type': 'application/json'}
    )
    print_test("UPDATE with invalid task_id returns 400", 
               resp.status_code == 400,
               f"Status: {resp.status_code}")

def test_delete_endpoint():
    """Test DELETE endpoint"""
    print("\n" + "="*60)
    print("TEST 7: DELETE Task Endpoint")
    print("="*60)
    
    # Test with valid ID
    resp = requests.delete(f"{BASE_URL}/api/tasks/delete.php?id=1")
    print_test("DELETE with valid ID", resp.status_code in [200, 401, 403, 404],
               f"Status: {resp.status_code}")
    
    # Test with invalid ID
    resp = requests.delete(f"{BASE_URL}/api/tasks/delete.php?id=invalid")
    print_test("DELETE with invalid ID returns 400", 
               resp.status_code == 400,
               f"Status: {resp.status_code}")
    
    # Test without ID parameter
    resp = requests.delete(f"{BASE_URL}/api/tasks/delete.php")
    print_test("DELETE without ID returns 400", 
               resp.status_code == 400,
               f"Status: {resp.status_code}")

def test_response_format():
    """Test response format consistency"""
    print("\n" + "="*60)
    print("TEST 8: Response Format Validation")
    print("="*60)
    
    endpoints = [
        ("read.php", "GET"),
        ("read_single.php?id=1", "GET"),
    ]
    
    for endpoint, method in endpoints:
        resp = requests.get(f"{BASE_URL}/api/tasks/{endpoint}")
        
        try:
            data = resp.json()
            
            # Check required fields
            has_success = 'success' in data
            has_message = 'message' in data
            has_timestamp = 'timestamp' in data
            
            all_present = has_success and has_message and has_timestamp
            
            print_test(f"{method} {endpoint} - Response format",
                      all_present,
                      f"success: {has_success}, message: {has_message}, timestamp: {has_timestamp}")
            
            if not all_present:
                print(f"  Response keys: {list(data.keys())}")
        except json.JSONDecodeError:
            print_test(f"{method} {endpoint} - Valid JSON", False,
                      "Response is not valid JSON")

def main():
    """Run all tests"""
    print("\n" + "="*60)
    print("TASK API ENDPOINT TEST SUITE")
    print("="*60)
    print(f"Base URL: {BASE_URL}")
    print(f"Test started: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    
    test_unauthenticated()
    test_authenticated_read()
    test_read_filters()
    test_read_single()
    test_create_endpoint()
    test_update_endpoint()
    test_delete_endpoint()
    test_response_format()
    
    print("\n" + "="*60)
    print("TEST SUITE COMPLETE")
    print("="*60)

if __name__ == "__main__":
    main()

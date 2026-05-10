#!/usr/bin/env python3
"""
Diagnostic script to capture actual error responses
"""

import requests
import json

BASE_URL = "http://localhost/SideKick"
session = requests.Session()

# Login
payload = {'email': 'admin@test.com', 'password': 'AdminPass123'}
resp = session.post(f"{BASE_URL}/api/auth/login.php", json=payload, headers={'Content-Type': 'application/json'})
print(f"Login status: {resp.status_code}")
print(f"Login response: {resp.text[:500]}\n")

# Test READ endpoint
print("=" * 60)
print("Testing READ endpoint")
print("=" * 60)
resp = session.get(f"{BASE_URL}/api/tasks/read.php")
print(f"Status: {resp.status_code}")
print(f"Headers: {dict(resp.headers)}")
print(f"Response text:")
print(resp.text[:1000])

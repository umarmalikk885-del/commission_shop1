# Sidebar Optimization Script

This document tracks the optimization of sidebar styles across all view files.

## Files to Update:
1. ✅ dashboard.blade.php - DONE
2. ✅ sales.blade.php - DONE  
3. ✅ stock.blade.php - DONE
4. ✅ profile.blade.php - DONE
5. ⏳ dues.blade.php - IN PROGRESS
6. ⏳ dues-selling.blade.php - IN PROGRESS
7. ⏳ vendors.blade.php - IN PROGRESS
8. ⏳ purchase.blade.php - PENDING
9. ⏳ reports.blade.php - PENDING
10. ⏳ bank-cash.blade.php - PENDING
11. ⏳ product-owner.blade.php - PENDING
12. ⏳ settings.blade.php - PENDING
13. ⏳ settings/users.blade.php - PENDING
14. ⏳ settings/roles.blade.php - PENDING
15. ⏳ vendors/create.blade.php - PENDING
16. ⏳ vendors/edit.blade.php - PENDING
17. ⏳ welcome.blade.php - PENDING

## Optimization Steps:
1. Remove all duplicate `.sidebar` CSS definitions
2. Add `@include('components.main-content-spacing')` in head section
3. Update `.main` margin-left from 230px to use centralized component
4. Remove RTL sidebar positioning (handled by centralized component)
5. Ensure all files use `@include('components.sidebar')` for sidebar HTML

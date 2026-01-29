# SmartHarvest Icon System Documentation
## Complete Icon & Logo Reference Guide

---

## 🌱 Primary Logo

**SmartHarvest Seedling Logo**
- **Icon:** 🌱 (Seedling Emoji)
- **Usage:** Primary branding across all pages
- **Symbolism:** Growth, agriculture, sustainability, new beginnings
- **Color Scheme:** Green (#059669, #047857) for agricultural theme

### Logo Locations:
- Login page
- Registration page
- Homepage header
- Dashboard sidebar (all pages)
- Admin dashboard
- All authenticated pages
- Verification pages

---

## 📍 Icon Categories

### 1. Navigation & Menu Icons

#### **Dashboard**
```html
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
</svg>
```
**Purpose:** Home/Dashboard navigation  
**Color:** White on green sidebar  
**Used in:** All sidebar menus

#### **Planting Schedule**
```html
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
</svg>
```
**Purpose:** Calendar/scheduling features  
**Color:** White on green sidebar  
**Used in:** Planting schedule navigation

#### **Yield Analysis**
```html
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
</svg>
```
**Purpose:** Analytics/statistics  
**Color:** White on green sidebar  
**Used in:** Yield analysis navigation

#### **Weather Forecast**
```html
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
</svg>
```
**Purpose:** Weather information  
**Color:** White on green sidebar, Blue (#2563eb) on cards  
**Used in:** Weather forecast navigation and cards

#### **Settings**
```html
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
</svg>
```
**Purpose:** Account settings  
**Color:** White on green sidebar  
**Used in:** Settings navigation

#### **Logout**
```html
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
</svg>
```
**Purpose:** Sign out  
**Color:** White on green sidebar  
**Used in:** Logout button

---

### 2. Dashboard Card Icons

#### **Expected Harvest (Growth/Trend)**
```html
<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
</svg>
```
**Purpose:** Harvest predictions and growth trends  
**Background:** Green (#f0fdf4)  
**Used in:** Year expected harvest card

#### **Planting Window (Calendar)**
```html
<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
</svg>
```
**Purpose:** Optimal planting dates  
**Background:** Green (#f0fdf4)  
**Used in:** Best planting window card

#### **Crop Recommendation (Star/Sparkle)**
```html
<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
</svg>
```
**Purpose:** Top crop recommendations  
**Background:** Green (#f0fdf4)  
**Used in:** Recommended variety card

---

### 3. Form & Input Icons

#### **User/Profile**
```html
<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
</svg>
```
**Purpose:** Name/user input fields  
**Color:** Gray (#9ca3af)  
**Used in:** Registration, login, profile forms

#### **Email**
```html
<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
</svg>
```
**Purpose:** Email input fields  
**Color:** Gray (#9ca3af)  
**Used in:** Registration, login, email verification

#### **Phone**
```html
<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
</svg>
```
**Purpose:** Phone number fields  
**Color:** Gray (#9ca3af), Green (#059669) for OTP  
**Used in:** Registration, SMS verification

#### **Password/Lock**
```html
<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M12 15v2m-6 4h12a1 1 0 001-1v-6a1 1 0 00-1-1H7a1 1 0 00-1 1v6a1 1 0 001 1zm10-10V7a4 4 0 00-8 0v4h8z"/>
</svg>
```
**Purpose:** Password input fields  
**Color:** Gray (#9ca3af)  
**Used in:** Login, registration, password reset

#### **Location/Municipality**
```html
<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
</svg>
```
**Purpose:** Location/municipality selection  
**Color:** Green (#059669)  
**Used in:** Registration, dashboard filters

---

### 4. Language & Translation Icons

#### **Language Selector**
```html
<svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
</svg>
```
**Purpose:** Multi-language support  
**Color:** Gray (#4b5563)  
**Used in:** All pages with language dropdown

---

### 5. Admin Dashboard Icons

#### **Admin Badge/Shield**
```html
<svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
</svg>
```
**Purpose:** Admin authentication and security  
**Color:** Blue (#1d4ed8)  
**Used in:** Admin login page

#### **Users Management**
```html
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
</svg>
```
**Purpose:** User management  
**Color:** White on blue sidebar  
**Used in:** Admin users section

#### **Roles & Permissions**
```html
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
</svg>
```
**Purpose:** Access control  
**Color:** White on blue sidebar  
**Used in:** Admin roles section

#### **Database/Datasets**
```html
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
</svg>
```
**Purpose:** Data management  
**Color:** White on blue sidebar  
**Used in:** Admin datasets section

#### **Data Import/Upload**
```html
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
</svg>
```
**Purpose:** CSV/data upload  
**Color:** White on blue sidebar  
**Used in:** Admin data import section

#### **Monitoring/Analytics**
```html
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
</svg>
```
**Purpose:** System monitoring  
**Color:** White on blue sidebar  
**Used in:** Admin monitoring section

---

### 6. Status & Indicator Icons

#### **Active/Connected (ML Status)**
```html
<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
</svg>
```
**Purpose:** ML API connection status  
**Color:** White on blue badge  
**Used in:** Dashboard ML status indicator

#### **Search**
```html
<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
</svg>
```
**Purpose:** Search functionality  
**Color:** Gray (#9ca3af)  
**Used in:** Dashboard header search

#### **Dropdown Arrow**
```html
<svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
</svg>
```
**Purpose:** Dropdown menus  
**Color:** Gray (#6b7280)  
**Used in:** All dropdown selectors

#### **Back Arrow**
```html
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
</svg>
```
**Purpose:** Back navigation  
**Color:** Gray (#4b5563)  
**Used in:** Settings and detail pages

---

### 7. Weather-Specific Icons

#### **Sun/Clear**
☀️ or 🌤️
**Purpose:** Sunny weather  
**Used in:** Weather forecast cards

#### **Rain**
🌧️ or ☔
**Purpose:** Rainy weather  
**Used in:** Weather forecast cards

#### **Clouds**
☁️ or ⛅
**Purpose:** Cloudy weather  
**Used in:** Weather forecast cards

#### **Storm**
⛈️
**Purpose:** Stormy weather  
**Used in:** Weather forecast cards

---

### 8. Agricultural Theme Icons

#### **Seedling** 🌱
**Primary Logo**  
**Symbolism:** Growth, new crops, sustainability  
**Used:** Throughout all pages as brand identity

#### **Tractor** 🚜
**Purpose:** Farming operations  
**Potential use:** Future features

#### **Harvest** 🌾
**Purpose:** Crop yield  
**Potential use:** Harvest tracking features

#### **Farm** 🏡
**Purpose:** Farm location  
**Potential use:** Farm management features

---

## 🎨 Color Scheme

### Primary Colors:
- **Green (#059669, #047857):** Agriculture, growth, primary actions
- **Light Green (#f0fdf4, #dcfce7):** Backgrounds, accents
- **Blue (#2563eb, #1d4ed8):** Weather, admin, secondary actions
- **Gray (#6b7280, #9ca3af, #f3f4f6):** Neutral elements, text

### Status Colors:
- **Green (#10b981):** Success, positive trends, live status
- **Blue (#3b82f6):** Info, predictions, ML status
- **Red (#ef4444):** Errors, negative trends, alerts
- **Yellow (#f59e0b):** Warnings, pending states

---

## 📱 Responsive Icon Sizes

### Size Guidelines:
- **Small (w-3 h-3):** 12px - Status indicators, badges
- **Medium (w-4 h-4):** 16px - Dropdown arrows, small buttons
- **Default (w-5 h-5):** 20px - Navigation icons, form icons
- **Large (w-6 h-6):** 24px - Card icons, headers
- **XL (w-8 h-8 / w-10 h-10):** 32-40px - Logo, primary branding
- **2XL (w-12 h-12):** 48px - Feature cards, major sections

---

## 🔄 Animation & States

### Hover Effects:
- Sidebar items: `background: rgba(255, 255, 255, 0.1)`
- Buttons: Darker shade of primary color
- Icons: Scale slightly (1.05x)

### Active States:
- Sidebar active: `background: rgba(255, 255, 255, 0.15)` + left border
- Selected items: Green background (#f0fdf4) + green text

### Loading States:
- Skeleton screens with gray placeholders
- Spinning loader icons for data fetching

---

## 📂 Icon Sources

All icons are from **Heroicons** (by Tailwind CSS):
- Type: Outline (stroke-based)
- License: MIT License
- CDN: Included via Tailwind CSS CDN
- Customization: Fill, stroke, viewBox properties

---

## 🛠️ Implementation Guidelines

### Adding New Icons:

1. **Choose appropriate icon** from Heroicons library
2. **Set consistent size** based on usage context
3. **Apply color scheme** matching component theme
4. **Add accessibility** attributes (aria-label)
5. **Test responsiveness** across devices

### Best Practices:

✅ Use consistent sizing within same component  
✅ Maintain color contrast for accessibility (4.5:1 minimum)  
✅ Provide text alternatives for screen readers  
✅ Use stroke-width="2" for consistency  
✅ Apply hover states for interactive icons  

❌ Don't mix fill and stroke icons  
❌ Don't use overly complex icons  
❌ Don't forget mobile-friendly touch targets  
❌ Don't override viewBox unless necessary  

---

## 📊 Icon Usage Statistics

**Total Unique Icons:** 30+  
**Most Used:** Dashboard, Calendar, Weather, User icons  
**Color Variations:** 5 primary, 4 status colors  
**Pages with Icons:** All 15+ pages  

---

## 🔮 Future Enhancements

### Planned Icons:
- 📊 Advanced analytics icons
- 🌡️ Temperature gauge icons
- 💧 Water/irrigation icons
- 🐛 Pest management icons
- 📱 Mobile app icons
- 🔔 Notification bells
- 📈 Trend indicators
- 🎯 Goal/target icons

---

## 📝 Quick Reference

### Common Icon Patterns:

**Navigation:** Home, Calendar, Chart, Cloud, Settings, Logout  
**Forms:** User, Email, Phone, Lock, Location  
**Actions:** Search, Upload, Download, Edit, Delete  
**Status:** Check, Warning, Error, Info, Loading  
**Data:** Database, Import, Export, Analytics  

---

**Last Updated:** January 20, 2026  
**Version:** 1.0  
**Maintained by:** SmartHarvest Development Team  
**Icon Library:** Heroicons + Custom Agricultural Emojis  

🌱 SmartHarvest - Grow Smarter, Harvest Better

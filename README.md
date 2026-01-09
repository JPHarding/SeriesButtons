# JP Series Buttons for OpenMage (Magento 1)

A lightweight OpenMage (Magento 1) module that shows up to **3 rows of button selectors** on the product page, allowing customers to **navigate between products in the same "Series"**.

- Buttons are simple hyperlinks (normal page loads)
- Highlights the current product option automatically
- Shows **unique values per row** (clean UI)

---

## What this module does

On a product page:

1. It checks if the product has a **Series Name**
2. It finds all other products with the same **Series Name**
3. It builds up to 3 rows of buttons:
   - Row 1 = unique values from `Series Row 1 Value`
   - Row 2 = unique values from `Series Row 2 Value`
   - Row 3 = unique values from `Series Row 3 Value`
4. When a button is clicked, it navigates to the **best matching product page** (tries to preserve current row selections).

---

## Example use case

Series Name: `Corsair LX-R Series`

Products:

- Row 1 Value: `Single Pack`, Row 2 Value: `120mm`, Row 3 Value: `Black`
- Row 1 Value: `Dual Pack`, Row 2 Value: `120mm`, Row 3 Value: `Black`
- Row 1 Value: `Single Pack`, Row 2 Value: `140mm`, Row 3 Value: `White`

Frontend result:

- Row 1 buttons: `Single Pack`, `Dual Pack`
- Row 2 buttons: `120mm`, `140mm`
- Row 3 buttons: `Black`, `White`

---

## Compatibility

- OpenMage (Magento 1)
- Default theme (base/default)
- PHP 5.6+ (should work fine on typical OpenMage setups)

---

## Installation

This module contains files that must be placed in different locations.
Follow the folder paths exactly.

### 1) Copy module declaration file

Copy:

`app/etc/modules/JP_SeriesButtons.xml`

to:

`YOUR_MAGENTO_ROOT/app/etc/modules/JP_SeriesButtons.xml`

---

### 2) Copy module code files

Copy the whole folder:

`app/code/local/JP/SeriesButtons/`

to:

`YOUR_MAGENTO_ROOT/app/code/local/JP/SeriesButtons/`

This includes:

- `etc/config.xml`
- `Block/Buttons.php`
- `sql/jp_seriesbuttons_setup/install-1.0.0.php`

---

### 3) Copy the layout file

Copy:

`app/design/frontend/base/default/layout/jp_seriesbuttons.xml`

to:

`YOUR_MAGENTO_ROOT/app/design/frontend/base/default/layout/jp_seriesbuttons.xml`

---

### 4) Copy the template file

Copy:

`app/design/frontend/base/default/template/jp/seriesbuttons/buttons.phtml`

to:

`YOUR_MAGENTO_ROOT/app/design/frontend/base/default/template/jp/seriesbuttons/buttons.phtml`

---

### 5) Copy the CSS file

Copy:

`skin/frontend/base/default/jp/seriesbuttons/buttons.css`

to:

`YOUR_MAGENTO_ROOT/skin/frontend/base/default/jp/seriesbuttons/buttons.css`

---

### 6) Add hook to `app/design/frontend/rwd/default/template/catalog/product/view.phtml`

1. Find css class you want to add this after. in my case its `.extra-info` in `product/view.phtml`
2. Add the following line after the `.extra-info` div.

```php
<?php echo $this->getChildHtml('jp_seriesbuttons'); ?>
```

---

### 7) Flush cache and trigger setup script

1. Go to Admin:
   `System - Cache Management`
2. Click:
   `Flush Magento Cache`

Then load any frontend page once.  
The install script will automatically create the product attributes.

Recommended:

- `System - Index Management - Reindex All`

---

## Admin usage (where users type the values)

Go to:

`Catalog - Manage Products`

Edit any product and scroll down in the **General** tab.

You will see 4 new attributes:

- **Series Name**
- **Series Row 1 Value**
- **Series Row 2 Value**
- **Series Row 3 Value**

### How to use it properly

To group products together:

- Set **Series Name** to the same value for all products in a series.

To set what buttons show:

- Fill Row 1, Row 2, and Row 3 values per product.

Example:

| Product   | Series Name         | Row 1 Value | Row 2 Value | Row 3 Value |
| --------- | ------------------- | ----------- | ----------- | ----------- |
| Product A | Corsair LX-R Series | Single Pack | 120mm       | Black       |
| Product B | Corsair LX-R Series | Dual Pack   | 120mm       | Black       |
| Product C | Corsair LX-R Series | Single Pack | 120mm       | White       |

---

## Frontend behavior

- The selector only shows if:
  - Series Name is filled in
  - There are at least 2 products in that series
- Each row only shows if it has at least **2 unique values**
- Buttons show as **unique values**, not duplicates
- The button matching the current product’s value is highlighted

---

## Best Match URL logic

When a user clicks a button, the module tries to find a product in the same series that matches:

1. Row1 + Row2 + Row3
2. Row1 + Row2
3. Row1 + Row3
4. Row2 + Row3
5. Just the clicked row
6. Fallback to the current product

This creates a smooth experience even if some product combinations do not exist.

---

## Customizing where it displays on the product page

By default it is inserted into the `content` block using this layout file:

`app/design/frontend/base/default/layout/jp_seriesbuttons.xml`

If you want it to display somewhere else (under price, under add-to-cart, etc) you can move the block reference, or ask your developer to adjust it.

---

## Styling

Buttons are styled via:

`skin/frontend/base/default/jp/seriesbuttons/buttons.css`

You can safely change:

- background
- border color
- spacing
- button size

---

## Troubleshooting

### Attributes are not showing in admin

- Flush cache
- Log out and log back in
- Make sure the setup script ran:
  - Check `core_resource` table for `jp_seriesbuttons_setup`

### Selector does not show on product page

- Ensure the current product has `Series Name` set
- Ensure at least 1 other product has the same `Series Name`
- Ensure products are enabled and visible

### CSS not loading

- Confirm the CSS file exists in:
  `skin/frontend/base/default/jp/seriesbuttons/buttons.css`
- Flush cache

---

## License

MIT (do whatever you want, just don’t blame me if you break your store)

---

## Author

JP

metator
=======

A unit tested shopping cart.

Why yet another open source shopping cart? There is a need for online retailers to sell complex products in today's marketplace. Configurable products that come in different colors & sizes like a T-Shirt, and groups of these configurable products like a product representing a T-Shirt & Pants sold together.

Existing solutions for this like Magento lack the ability to import this complex data, and are hard to modify beyond it's cookie cutter functionality.

Metator aims to utilize unit testing with a philosophy that import/export comes before GUI, with an emphasis on providing command line utilities for common operations. Furthermore, an emphasis will be placed upon performance. Page load times, importing, and exporting will be quick, even with large datasets.

Why the emphasis on Import? Existing solutions like Magento provide great features that aren't supported by their import/export. You can have multiple images per product in Magento, but there's no way to import or export them. You can add configurable options like "color" & "size", but only if you do so by hand one product at a time. This point & click style of setting up the store is fine for smaller datasets, but when there are large datasets to be loaded it poses multiple problems. First, is the cost of "man hours" to input the data, secondly the nature of humans is to make mistakes. When data accuracy is of upmost importance, store owners would rather rely on the computer to import their data quickly & accurately.

What about export? That is important too. If you imported it, you need to be able to export it. An export from within the application should be a substitute for backing up the database. If you export all data from within the app, then delete your database, importing it back in should restore you to a working state with no data loss. Exports & backups should be synonymous.

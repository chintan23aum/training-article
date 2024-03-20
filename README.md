# training-article-project
Training project

<h5>Admin Panel</h5>
<p>To access Admin panel user must be ROLE_ADMIN type user on users table</p>
<p>Admin can Manage Category and Articles</p>
<ul>
    <li><h4>Category</h4>
        -> Admin can manage N level category by selecting multiple Parent Category<br/>
        -> Admin can Add/Edit/Delete Categories<br/>
    </li>
    <li><h4>Article</h4>
        -> Admin can manage article which list under selected category<br/>
        -> Admin can Add/Edit/Delete Categories<br/>
    </li>
</ul>

<h5>Frontend</h5>
<p>On frontend page user can see Articles based on categories, on Home page it list all Articles and once user select any category from menu OR filter by advance search then it list selected Articles.</p>

<p>Top menu list all main categories which does not have any parent categories.</p>

<p>Left menu list all categories with sub categories</p>

<p>User can filter Article by using advance search which provide filter for Category, Tags, Title.</p>

<p>Only LoggedIn user can see Full Article so click on Read More button under article list page will redirect user to login page.</p>

define({
  "name": "Sparta.games",
  "description": "Sparta.games API Documentation",
  "title": "Sparta.games API Documentation",
  "version": "1.0.0",
  "url": "http://sparta.games/api",
  "template": {
    "withCompare": true,
    "withGenerator": true
  },
  "header": {
    "title": "API Overview",
    "content": "<h2><strong>Usage Overview</strong></h2>\n<p>Here are some information that should help you understand the basic usage of our RESTful API.\nIncluding info about authenticating users, making requests, responses, potential errors, rate limiting, pagination, query parameters and more.</p>\n<h2><strong>Headers</strong></h2>\n<p>Certain API calls require you to send data in a particular format as part of the API call.\nBy default, all API calls expect input in <code>JSON</code> format, however you need to inform the server that you are sending a JSON-formatted payload.\nAnd to do that you must include the <code>Accept =&gt; application/json</code> HTTP header with every call.</p>\n<table>\n<thead>\n<tr>\n<th>Header</th>\n<th>Value Sample</th>\n<th>When to send it</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>Accept</td>\n<td><code>application/json</code></td>\n<td>MUST be sent with every endpoint.</td>\n</tr>\n<tr>\n<td>Content-Type</td>\n<td><code>application/x-www-form-urlencoded</code></td>\n<td>MUST be sent when passing Data.</td>\n</tr>\n<tr>\n<td>Authorization</td>\n<td><code>Bearer {Access-Token-Here}</code></td>\n<td>MUST be sent whenever the endpoint requires (Authenticated User).</td>\n</tr>\n</tbody>\n</table>\n<h2><strong>Tokens</strong></h2>\n<p>The Access Token lives for <code>expires_in</code>. More information about token get from login oauth.\n<em>You will need to re-authenticate the user when the token expires.</em></p>\n<h2><strong>Responses</strong></h2>\n<p>Unless otherwise specified, all of API endpoints will return the information that you request in the JSON data format.</p>\n<h4>Standard Response Format without pagination</h4>\n<pre><code class=\"language-shell\">{\n    {\n      &quot;id&quot;: 29,\n      &quot;title&quot;: &quot;Stivy G&quot;,\n      &quot;slug&quot;: &quot;stivy-g&quot;,\n      &quot;image&quot;: &quot;teams/yCXCT5GREiDRKZ9GmC4Vym8KtGJaUeqsuXtl2eGR.jpeg&quot;,\n      &quot;capt_id&quot;: 111,\n      &quot;created_at&quot;: null,\n      &quot;updated_at&quot;: null,\n      &quot;quantity&quot;: 3,\n      &quot;overlay&quot;: &quot;teams/xBfed3etEmphVwaEF4E9ZLc4KiXtOtLtJFdARsNG.jpeg&quot;,\n      &quot;game_id&quot;: 36,\n      &quot;balance&quot;: 0,\n      &quot;status&quot;: 1,\n      &quot;total_sum&quot;: 0,\n      &quot;count_wins&quot;: 0,\n      &quot;count_losses&quot;: 0,\n      &quot;count_fights&quot;: 0,\n      &quot;payed_dividents&quot;: 0\n    },\n    {\n      &quot;id&quot;: 30,\n      &quot;title&quot;: &quot;Team-dota&quot;,\n      &quot;slug&quot;: &quot;team-dota&quot;,\n      &quot;image&quot;: &quot;teams/ayGURgScUHONXpFndFDHKUUF1r88rhOuERm2d43V.jpeg&quot;,\n      &quot;capt_id&quot;: 111,\n      &quot;created_at&quot;: null,\n      &quot;updated_at&quot;: null,\n      &quot;quantity&quot;: 4,\n      &quot;overlay&quot;: &quot;teams/VNy2UNm1yUqo74shnPZfxDCp7b9DEhupsdCEs2pb.jpeg&quot;,\n      &quot;game_id&quot;: 5,\n      &quot;balance&quot;: 0,\n      &quot;status&quot;: 0,\n      &quot;total_sum&quot;: 0,\n      &quot;count_wins&quot;: 0,\n      &quot;count_losses&quot;: 0,\n      &quot;count_fights&quot;: 0,\n      &quot;payed_dividents&quot;: 0\n    },\n    ....\n}\n</code></pre>\n<h4>Header</h4>\n<p>Header Response:</p>\n<pre><code>Content-Type → application/json\nDate → Thu, 14 Feb 2014 22:33:55 GMT\nETag → &quot;9c83bf4cf0d09c34782572727281b85879dd4ff6&quot;\nServer → nginx\nTransfer-Encoding → chunked\nX-Powered-By → PHP/7.0.9\nX-RateLimit-Limit → 100\nX-RateLimit-Remaining → 99\n</code></pre>\n<h3>Caching</h3>\n<p>Some endpoints stores their response data in memory (caching) after querying them for the first time, to speed up the response time.\nThe <code>?skipCache=</code> parameter can be used to force skip loading the response data from the server cache and instead get a fresh data from the database upon the request.</p>\n<p><strong>Usage:</strong></p>\n<pre><code>sparta.games/api/endpoint?skipCache=true\n</code></pre>\n<h2>Filtering</h2>\n<p>Every query parameter, except the predefined functions <code>_fields</code>, <code>_with</code>, <code>_sort</code>, <code>_limit</code>, <code>page</code> and <code>_q</code>, is interpreted as a filter. Be sure to remove additional parameters not meant for filtering before passing them to collection.</p>\n<pre><code>/api/teams?title=The Lord of the Rings\n</code></pre>\n<p>All the filters are combined with an <code>AND</code> operator.</p>\n<pre><code>/api/teams?title-lk=The Lord*&amp;created_at-min=2014-03-14 12:55:02\n</code></pre>\n<p>The above example would result in the following SQL where:</p>\n<pre><code class=\"language-sql\">WHERE `title` LIKE &quot;The Lord%&quot; AND `created_at` &gt;= &quot;2014-03-14 12:55:02&quot;\n</code></pre>\n<p>Its also possible to use multiple values for one filter. Multiple values are separated by a pipe <code>|</code>.\nMultiple values are combined with <code>OR</code> except when there is a <code>-not</code> suffix, then they are combined with <code>AND</code>.\nFor example all the teams with the id 5 or 6:</p>\n<pre><code>/api/teams?id=5|6\n</code></pre>\n<p>Or all the teams except the ones with id 5 or 6:</p>\n<pre><code>/api/teams?id-not=5|6\n</code></pre>\n<p>The same could be achieved using the <code>-in</code> suffix:</p>\n<pre><code>/api/teams?id-in=5,6\n</code></pre>\n<p>Respectively the <code>not-in</code> suffix:</p>\n<pre><code>/api/teams?id-not-in=5,6\n</code></pre>\n<h4>Suffixes</h4>\n<table>\n<thead>\n<tr>\n<th>Suffix</th>\n<th>Operator</th>\n<th>Meaning</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>-lk</td>\n<td>LIKE</td>\n<td>Same as the SQL <code>LIKE</code> operator</td>\n</tr>\n<tr>\n<td>-not-lk</td>\n<td>NOT LIKE</td>\n<td>Same as the SQL <code>NOT LIKE</code> operator</td>\n</tr>\n<tr>\n<td>-in</td>\n<td>IN</td>\n<td>Same as the SQL <code>IN</code> operator</td>\n</tr>\n<tr>\n<td>-not-in</td>\n<td>NOT IN</td>\n<td>Same as the SQL <code>NOT IN</code> operator</td>\n</tr>\n<tr>\n<td>-min</td>\n<td>&gt;=</td>\n<td>Greater than or equal to</td>\n</tr>\n<tr>\n<td>-max</td>\n<td>&lt;=</td>\n<td>Smaller than or equal to</td>\n</tr>\n<tr>\n<td>-st</td>\n<td>&lt;</td>\n<td>Smaller than</td>\n</tr>\n<tr>\n<td>-gt</td>\n<td>&gt;</td>\n<td>Greater than</td>\n</tr>\n<tr>\n<td>-not</td>\n<td>!=</td>\n<td>Not equal to</td>\n</tr>\n</tbody>\n</table>\n<h3>Sorting</h3>\n<p>Two ways of sorting, ascending and descending. Every column which should be sorted descending always starts with a <code>-</code>.</p>\n<pre><code>/api/teams?_sort=-title,created_at\n</code></pre>\n<h3>Fulltext search</h3>\n<p>Two implementations of full text search are supported.</p>\n<p><em><strong>Note:</strong></em> When using an empty <code>_q</code> param the search will always return an empty result.</p>\n<p><strong>Limited custom implementation (default)</strong></p>\n<p>A given text is split into keywords which then are searched in the database. Whenever one of the keyword exists, the corresponding row is included in the result set.</p>\n<pre><code>/api/teams?_q=The Lord of the Rings\n</code></pre>\n<p>The above example returns every row that contains one of the keywords <code>The</code>, <code>Lord</code>, <code>of</code>, <code>the</code>, <code>Rings</code> in one of its columns.</p>\n<p>Each result will also contain a <code>_score</code> column which allows you to sort the results according to how well they match with the search terms. E.g.</p>\n<pre><code>/api/teams?_q=The Lord of the Rings&amp;_sort=-_score\n</code></pre>\n<h3>Limit the result set</h3>\n<p>To define the maximum amount of datasets in the result, use <code>_limit</code>.</p>\n<pre><code>/api/teams?_limit=50\n</code></pre>\n<p>To define the page of the datasets in the result, use <code>page</code>.</p>\n<pre><code>/api/teams?page=2&amp;_limit=50\n</code></pre>\n<h3>Paginating</h3>\n<p>The <code>?page=</code> parameter can be applied to any <strong><code>GET</code></strong> HTTP request responsible for listing records (mainly for Paginated data).</p>\n<p><strong>Usage:</strong></p>\n<pre><code>sparta.games/api/teams?page=1&amp;_limit=1\n</code></pre>\n<h4>Standard Response Format when pagination is available</h4>\n<pre><code class=\"language-shell\">  {\n    &quot;current_page&quot;: 1,\n    &quot;data&quot;: [...],\n    &quot;from&quot;: 1,\n    &quot;last_page&quot;: 2,\n    &quot;next_page_url&quot;: &quot;http://sparta.games/api/teams?page=2&quot;,\n    &quot;path&quot;: &quot;http://sparta.games/api/teams&quot;,\n    &quot;per_page&quot;: 1,\n    &quot;prev_page_url&quot;: null,\n    &quot;to&quot;: 1,\n    &quot;total&quot;: 2\n  }\n</code></pre>\n<p>You can skip the pagination limit to get all the data, by adding <code>?_limit=0</code>, this will only work if 'skip pagination' is enabled on the server.</p>\n<h3>Relationships</h3>\n<p>The api handler also supports Eloquent relationships. So if you want to get all the teams with their players, just add the users to the <code>_with</code> parameter.</p>\n<pre><code>/api/teams?_with=users\n</code></pre>\n<p>Relationships, can also be nested:</p>\n<pre><code>/api/teams?_with=users.game\n</code></pre>\n<p><em><strong>Note:</strong></em> Whenever you limit the fields with <code>_fields</code> in combination with <code>_with</code>. Under the hood the fields are extended with the primary/foreign keys of the relation. Eloquent needs the linking keys to get related models.</p>\n<h2><strong>Errors</strong> (Outdated)</h2>\n<p>General Errors:</p>\n<table>\n<thead>\n<tr>\n<th>Error Code</th>\n<th>Message</th>\n<th>Reason</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>401</td>\n<td>Wrong number of segments.</td>\n<td>Wrong Token.</td>\n</tr>\n<tr>\n<td>401</td>\n<td>Failed to authenticate because of bad credentials or an invalid authorization header.</td>\n<td>Missing parts of the Token.</td>\n</tr>\n<tr>\n<td>401</td>\n<td>Could not decode token: The token ... is an invalid JWS.</td>\n<td>Missing Token.</td>\n</tr>\n<tr>\n<td>405</td>\n<td>Method Not Allowed.</td>\n<td>Wrong Endpoint URL.</td>\n</tr>\n<tr>\n<td>422</td>\n<td>Invalid Input.</td>\n<td>Validation Error.</td>\n</tr>\n<tr>\n<td>500</td>\n<td>Internal Server Error.</td>\n<td>{Report this error as soon as you get it.}</td>\n</tr>\n<tr>\n<td>500</td>\n<td>This action is unauthorized.</td>\n<td>Using wrong HTTP Verb. OR using unauthorized token.</td>\n</tr>\n</tbody>\n</table>\n<h2><strong>Requests</strong></h2>\n<p>Calling unprotected endpoint example:</p>\n<pre><code class=\"language-shell\">curl -X POST -H &quot;Accept: application/json&quot; -H &quot;Content-Type: application/x-www-form-urlencoded; -F &quot;email=admin@admin.com&quot; -F &quot;password=admin&quot; -F &quot;=&quot; &quot;http://sparta.games/api/v2/register&quot;\n</code></pre>\n<p>Calling protected endpoint (passing Bearer Token) example:</p>\n<pre><code class=\"language-shell\">curl -X GET -H &quot;Accept: application/json&quot; -H &quot;Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...&quot; -H &quot;http://sparta.games/api/users&quot;\n</code></pre>\n"
  },
  "sampleUrl": false,
  "defaultVersion": "0.0.0",
  "apidoc": "0.3.0",
  "generator": {
    "name": "apidoc",
    "time": "2017-09-08T10:22:15.374Z",
    "url": "http://apidocjs.com",
    "version": "0.17.6"
  }
});

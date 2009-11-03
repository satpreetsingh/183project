<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<!-- This defines the transformation of the XML session tag.
	produces a li of buttons (I know ugly, CSS to the rescue)  
	to the course page with a short description with a drop button -->
<xsl:template match="sessionList">
	<ul>
		<xsl:for-each select="session">
			<li>
				<form action="CoursePageView.php" method="post">
					<button class="course" name="sesison">
					<xsl:attribute name="value"><xsl:value-of select="@id"/></xsl:attribute>
					<xsl:value-of select="@course"/>: <xsl:value-of select="."/>, 
					<xsl:value-of select="@department"/>, <xsl:value-of select="@university"/>
					</button>
				</form>
				<form action="DropCourse.php?from=UserHomePageView.php" method="post">
					<button class="drop" name="sesison" onclick="return confirm('Really? Drop the course?');">
					<xsl:attribute name="value"><xsl:value-of select="@id"/></xsl:attribute>
					Drop
					</button>
				</form>
			</li>
		</xsl:for-each>
	</ul>
</xsl:template>

<!-- Define other templates for other element types (eg user, note...)-->

</xsl:stylesheet>

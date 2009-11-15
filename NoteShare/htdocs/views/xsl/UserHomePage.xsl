<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:key name="session-by-uni" match="SessionUserItem" use="@University_Name" />

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<!-- This defines the transformation of the XML session tag.
	produces a li of buttons (I know ugly, CSS to the rescue)  
	to the course page with a short description with a drop button -->
<xsl:template match="UserSessionList">
	<xsl:for-each select="SessionUserItem[count(. | key('session-by-uni', @University_Name)[1]) = 1]">
		<div class="university">
			
	   		<h3 class="university"><xsl:value-of select="@University_Name" /></h3>
	   		<ul>
		   		<xsl:for-each select="key('session-by-uni', @University_Name)">
		   			<li>
			   			<a target="iframe_canvas">
							<xsl:attribute name="href">
								/views/CoursePage.php?ns_session=<xsl:value-of select="@Id" />
							</xsl:attribute>
							<xsl:value-of select="." />
						</a>
						[<a target="iframe_canvas" onclick="confirm('Really? Drop the course?');">
					    	<xsl:attribute name="href">
				      			/controllers/DropCourse.php?ns_session=<xsl:value-of select="@Id" />
				     		</xsl:attribute>
							X
						</a>]
					</li>
		   		</xsl:for-each>
		   	</ul>
		</div>
	</xsl:for-each>
</xsl:template>

</xsl:stylesheet>

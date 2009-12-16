<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:key name="session-by-uni" match="SessionUserItem" use="@University_Name" />
<xsl:key name="group-by-uni" match="GroupUserItem" use="@University_Name" />

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="UserSessionList">
	<xsl:for-each select="SessionUserItem[count(. | key('session-by-uni', @University_Name)[1]) = 1]">
		<div class="university">
			
	   		<h3 class="university"><xsl:value-of select="@University_Name" /></h3>
	   		<ul>
		   		<xsl:for-each select="key('session-by-uni', @University_Name)">
		   			<li>
		   			<a target="_top">
							<xsl:attribute name="href">http://apps.facebook.com/notesharesep/views/CoursePage.php?ns_session=<xsl:value-of select="@Id" /></xsl:attribute>
							<xsl:value-of select="." />
						</a>
						[<a target="_top" onclick="return confirm('Really? Drop the course?');">
					    	<xsl:attribute name="href">http://apps.facebook.com/notesharesep/controllers/DropCourse.php?ns_session=<xsl:value-of select="@Id" /></xsl:attribute>
							X
						</a>]
					</li>
		   		</xsl:for-each>
		   	</ul>
		</div>
	</xsl:for-each>
</xsl:template>

<xsl:template match="UserGroupList">
	<xsl:for-each select="GroupUserItem[count(. | key('group-by-uni', @University_Name)[1]) = 1]">
		<div class="university">
	   		<h3 class="university"><xsl:value-of select="@University_Name" /></h3>
	   		<ul>
		   		<xsl:for-each select="key('group-by-uni', @University_Name)">
		   			<li>
		   			<a target="_top">
							<xsl:attribute name="href">http://apps.facebook.com/notesharesep/views/GroupPage.php?ns_session=<xsl:value-of select="@SessionId" />&amp;nsStudyGroup=<xsl:value-of select="@Id" /></xsl:attribute>
                <xsl:value-of select="@Course_Name" /> - <xsl:text disable-output-escaping="yes"> </xsl:text><xsl:value-of select="@Session_Name" /> - <xsl:value-of select="." />
						</a>
						[<a target="_top" onclick="return confirm('Really? Drop the course?');">
					    	<xsl:attribute name="href">http://apps.facebook.com/notesharesep/controllers/DropGroup.php?ns_session=<xsl:value-of select="@SessionId" />&amp;nsStudyGroup=<xsl:value-of select="@Id" /></xsl:attribute>
							X
						</a>]
					</li>
		   		</xsl:for-each>
		   	</ul>
		</div>
	</xsl:for-each>
</xsl:template>

</xsl:stylesheet>

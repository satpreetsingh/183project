<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:fb="http://www.facebook.com/2008/fbml" 
>

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="SessionMetaData">
  <table class="sessionMetaData">
    <tr><td class="fbFont sessionName"><xsl:value-of select='.' /></td></tr>
    <tr><td class="fbFont sessionTime">Start Date: <xsl:value-of select="@Start_Date"/></td><td class="sessionTime">End Date: <xsl:value-of select="@End_Date" /></td></tr>
	  <tr><td class="fbFont sessionDescr">Description:<p><xsl:value-of select="@Desc"/></p></td></tr>
  </table>
</xsl:template>

<xsl:template match="memberList">
	<div>
		<xsl:for-each select="member">
			<xsl:sort select="@friend" order="descending" />
			<div>
				<xsl:attribute name="class">
					<xsl:choose>
						<xsl:when test="@friend='True'">friend</xsl:when>
						<xsl:otherwise>notFriend</xsl:otherwise>
					</xsl:choose>
				</xsl:attribute>
				<fb:profile-pic linked="true">
					<xsl:attribute name="uid"><xsl:value-of select="@id"/></xsl:attribute>
				</fb:profile-pic>
				<fb:name>
					<xsl:attribute name="uid"><xsl:value-of select="@id"/></xsl:attribute>
				</fb:name>
				</div>
		</xsl:for-each> 
	</div>
</xsl:template>

</xsl:stylesheet>
